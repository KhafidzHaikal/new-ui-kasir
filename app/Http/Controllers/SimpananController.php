<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Models\Member;
use App\Models\Setting;
use App\Models\Simpanan;
use App\Models\simpanan_induk;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('simpanan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $simpanan = Simpanan::join('member', 'simpanan.id_member', '=', 'member.id_member')
            ->join('simpanan_induks', 'simpanan.id_simpanan_induks', '=', 'simpanan_induks.id')
            ->select(
                'simpanan.*',
                'member.kode_member',
                'member.nama as nama_member',
                'simpanan_induks.nominal'
            )
            ->orderBy('id_simpanan', 'desc')->get();

        return datatables()
            ->of($simpanan)
            ->addIndexColumn()
            ->addColumn('created_at', function ($simpanan) {
                return tanggal_indonesia($simpanan->updated_at, false);
            })
            ->addColumn('kode_member', function ($simpanan) {
                $member = $simpanan->kode_member;
                return '<span class="label label-success">' . $member . '</span>';
            })
            ->addColumn('bayar_pokok', function ($simpanan) {
                if ($simpanan->bayar_pokok == $simpanan->nominal) {
                    return '<span class="label label-success">' . format_uang($simpanan->bayar_pokok) . '</span>';
                } else {
                    return '<span class="label label-danger">' . format_uang($simpanan->bayar_pokok) . '</span>';
                }
            })
            ->addColumn('bayar_wajib', function ($simpanan) {
                return format_uang($simpanan->bayar_wajib);
            })
            ->addColumn('bayar_manasuka', function ($simpanan) {
                return format_uang($simpanan->bayar_manasuka);
            })
            ->addColumn('aksi', function ($simpanan) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="deleteData(`' . route('simpanan.destroy', $simpanan->id_simpanan) . '`)" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                    ';
            })
            ->rawColumns(['aksi', 'kode_member', 'bayar_pokok'])
            ->make(true);
    }

    public function create()
    {
        $simpanan = new Simpanan();
        $simpanan->id_member = null;
        $simpanan->id_simpanan_induks = 1;
        $simpanan->bayar_pokok = 0;
        $simpanan->bayar_wajib = 0;
        $simpanan->bayar_manasuka = 0;
        $simpanan->save();

        session(['id_simpanan' => $simpanan->id_simpanan]);
        return redirect()->route('simpanan.transaksi');
    }

    public function transaksi()
    {
        $member = Member::orderBy('nama')->get();
        $data = response()->json($member);

        
        if ($id_simpanan = session('id_simpanan')) {
            $simpanan = Simpanan::find($id_simpanan);
            $memberSelected = $simpanan->member ?? new Member();
            $simpanan_induk = simpanan_induk::first();

            return view('simpanan.transaksi.index', compact('data', 'member', 'id_simpanan', 'memberSelected', 'simpanan_induk'));
        } else {
            return redirect()->route('simpanan.transaksi');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $member_gaji = Member::findOrFail($request->id_member);
        $member_gaji->simpanan_pokok += $request->bayar_pokok;
        // dd($member_gaji->simpanan_pokok);
        $simpanan = Simpanan::findOrFail($request->id_simpanan);
        $simpanan->id_member = $request->id_member;
        $simpanan->id_simpanan_induks = 1;
        $simpanan->bayar_pokok = $member_gaji->simpanan_pokok;
        $simpanan->bayar_wajib = $request->bayar_wajib;
        $simpanan->bayar_manasuka = $request->bayar_manasuka;

        $simpanan->update();
        $member_gaji->update();

        return redirect()->route('simpanan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Simpanan  $simpanan
     * @return \Illuminate\Http\Response
     */
    public function show(Simpanan $simpanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Simpanan  $simpanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Simpanan $simpanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Simpanan  $simpanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Simpanan $simpanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Simpanan  $simpanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Simpanan $simpanan)
    {
        //
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('simpanan.transaksi.selesai', compact('setting'));
    }
}
