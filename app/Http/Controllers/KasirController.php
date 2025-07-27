<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produk;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.index_kasir');
    }

    public function data()
    {
        if (auth()->user()->level == 1) {
            $penjualan = Penjualan::with('member')->orderBy('id_penjualan', 'desc')->get();
        } elseif (auth()->user()->level == 2) {
            $penjualan = Penjualan::with('member')
                ->join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 2)
                ->orWhere('users.level', 6)
                ->orderBy('penjualan.id_penjualan', 'desc')
                ->get();
        } elseif (auth()->user()->level == 4) {
            $penjualan = Penjualan::with('member')
                ->join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 4)
                ->orderBy('penjualan.id_penjualan', 'desc')
                ->get();
        } elseif (auth()->user()->level == 5) {
            $penjualan = Penjualan::with('member')
                ->join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 5)
                ->orWhere('users.level', 8)
                ->orderBy('penjualan.id_penjualan', 'desc')
                ->get();
        } elseif (auth()->user()->level == 8) {
            $penjualan = Penjualan::with('member')
                ->join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 8)
                ->orderBy('penjualan.id_penjualan', 'desc')
                ->get();
        } else {
            $penjualan = Penjualan::with('member')
                ->where('id_user', '=', Auth::id())
                ->orderBy('id_penjualan', 'desc')
                ->get();
        }

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('kode_member', function ($penjualan) {
                $member = $penjualan->member->kode_member ?? '';
                return '<span class="label label-success">' . $member . '</span>';
            })
            ->addColumn('pembayaran', function ($penjualan) {
                if ($penjualan->pembayaran == 'kredit') {
                    return '<span class="label label-warning" style="text-transform: capitalize">' . $penjualan->pembayaran . '</span>';
                } else {
                    return '<span class="label label-primary" style="text-transform: capitalize">' . $penjualan->pembayaran . '</span>';
                }
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member', 'pembayaran'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function laporan($awal, $akhir)
    {
        $akhir = Carbon::parse($akhir)->endOfDay();
        if (auth()->user()->level == 1) {
            // $penjualan = PenjualanDetail::join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->select('penjualan_detail.*', 'penjualan.*')
            //     ->whereBetween('penjualan_detail.created_at', [$awal, $akhir])
            //     ->get();
            $penjualan = Penjualan::whereBetween('created_at', [$awal, $akhir])->get();
        } elseif (auth()->user()->level == 2) {
            // $penjualan = PenjualanDetail::join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->join('users', 'penjualan.id_user', '=', 'users.id')
            //     ->where('users.level', 2)
            //     ->orWhere('users.level', 6)
            //     ->select('penjualan_detail.*', 'penjualan.*')
            //     ->whereBetween('penjualan_detail.created_at', [$awal, $akhir])
            //     ->get();
            $penjualan = Penjualan::join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 2)
                ->orWhere('users.level', 6)
                ->whereBetween('created_at', [$awal, $akhir])
                ->get();
        } elseif (auth()->user()->level == 4) {
            // $penjualan = PenjualanDetail::join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->join('users', 'penjualan.id_user', '=', 'users.id')
            //     ->where('users.level', 4)
            //     ->select('penjualan_detail.*', 'penjualan.*')
            //     ->whereBetween('penjualan_detail.created_at', [$awal, $akhir])
            //     ->get();
            $penjualan = Penjualan::join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 4)
                ->whereBetween('created_at', [$awal, $akhir])
                ->get();
        } elseif (auth()->user()->level == 5) {
            // $penjualan = PenjualanDetail::join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->join('users', 'penjualan.id_user', '=', 'users.id')
            //     ->where('users.level', 5)
            //     ->select('penjualan_detail.*', 'penjualan.*')
            //     ->whereBetween('penjualan_detail.created_at', [$awal, $akhir])
            //     ->get();
            $penjualan = Penjualan::join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 5)
                ->orWhere('users.level', 8)
                ->whereBetween('created_at', [$awal, $akhir])
                ->get();
        } elseif (auth()->user()->level == 8) {
            // $penjualan = PenjualanDetail::join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->join('users', 'penjualan.id_user', '=', 'users.id')
            //     ->where('users.level', 5)
            //     ->select('penjualan_detail.*', 'penjualan.*')
            //     ->whereBetween('penjualan_detail.created_at', [$awal, $akhir])
            //     ->get();
            $penjualan = Penjualan::join('users', 'penjualan.id_user', '=', 'users.id')
                ->where('users.level', 8)
                ->whereBetween('created_at', [$awal, $akhir])
                ->get();
        } else {
            // $penjualan = PenjualanDetail::join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            //     ->where('penjualan.id_user', Auth::id())
            //     ->select('penjualan_detail.*', 'penjualan.*')
            //     ->whereBetween('penjualan_detail.created_at', [$awal, $akhir])
            //     ->get();
            $penjualan = Penjualan::where('penjualan.id_user', Auth::id())
                ->whereBetween('created_at', [$awal, $akhir])
                ->get();
        }

        // dd($penjualan);

        $no = 1;
        $total = 0;
        $total_kredit = 0;
        $total_tunai = 0;
        foreach ($penjualan as $item) {
            $total += $item->bayar;

            if ($item->pembayaran == 'kredit') {
                $total_kredit += $item->bayar;
            } else {
                $total_tunai += $item->bayar;
            }
        }

        $pdf  = PDF::loadView('kasir.laporan', compact('awal', 'akhir', 'penjualan', 'total', 'no', 'total_kredit', 'total_tunai'));
        return $pdf->inline('Laporan-Penjualan-' . date('Y-m-d-his') . '.pdf');
    }
}
