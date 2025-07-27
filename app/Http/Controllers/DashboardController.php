<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\PenjualanDetail;
use App\Models\PembelianDetail;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::count();

        if (auth()->user()->level == 4) {
            $produk = Produk::where('id_kategori', 4)->count();
        } elseif (auth()->user()->level == 5 || auth()->user()->level == 8) {
            $produk = Produk::where('id_kategori', 5)->count();
        } elseif (auth()->user()->level == 1) {
            $produk = Produk::count();

            $expired_products = Produk::where([['tanggal_expire', '<=', Carbon::now()->addDays(7)], ['id_kategori', '!=', 4], ['id_kategori', '!=', 5]])
                ->whereNotNull('tanggal_expire')
                ->get();

            if ($expired_products->isNotEmpty()) {
                Alert::warning('Produk Kadaluarsa', "Halo, " . $expired_products->count() . " Produk yang habis masa berlaku dalam 7 hari ke depan. Harap pastikan untuk mengelola stok produk Anda.");
            }
        } else {
            $expired_products = Produk::where([['tanggal_expire', '<=', Carbon::now()->addDays(7)], ['id_kategori', '!=', 4], ['id_kategori', '!=', 5]])
                ->whereNotNull('tanggal_expire')
                ->get();
            $produk = Produk::where([['id_kategori', '!=', 4], ['id_kategori', '!=', 5]])->count();

            if ($expired_products->isNotEmpty()) {
                Alert::warning('Produk Kadaluarsa', "Halo, " . $expired_products->count() . " Produk yang habis masa berlaku dalam 7 hari ke depan. Harap pastikan untuk mengelola stok produk Anda.");
            }
        }

        $supplier = Supplier::count();
        $member = Member::count();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        // Get Top 5 Barang Terjual (berdasarkan jumlah)
        $top_penjualan = $this->getTopPenjualan();
        
        // Get Top 5 Barang Pembelian (berdasarkan jumlah)
        $top_pembelian = $this->getTopPembelian();

        // Get recent transactions
        $recent_penjualan = $this->getRecentPenjualan();
        $recent_pembelian = $this->getRecentPembelian();

        // Get monthly statistics
        $monthly_stats = $this->getMonthlyStats();

        if (auth()->user()->level == 6 || auth()->user()->level == 8) {
            return view('kasir.dashboard');
        } else {
            return view('admin.dashboard', compact(
                'kategori', 
                'produk', 
                'supplier', 
                'member', 
                'tanggal_awal', 
                'tanggal_akhir', 
                'data_tanggal', 
                'data_pendapatan',
                'top_penjualan',
                'top_pembelian',
                'recent_penjualan',
                'recent_pembelian',
                'monthly_stats'
            ));
        }
    }

    /**
     * Get Top 5 Barang Terjual berdasarkan jumlah
     */
    private function getTopPenjualan()
    {
        return PenjualanDetail::select('id_produk', DB::raw('SUM(jumlah) as total_terjual'))
            ->with(['produk' => function($query) {
                $query->select('id_produk', 'nama_produk', 'harga_jual');
            }])
            ->groupBy('id_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'nama_produk' => $item->produk->nama_produk ?? 'Produk Tidak Ditemukan',
                    'total_terjual' => $item->total_terjual,
                    'harga_jual' => $item->produk->harga_jual ?? 0,
                    'total_pendapatan' => $item->total_terjual * ($item->produk->harga_jual ?? 0)
                ];
            });
    }

    /**
     * Get Top 5 Barang Pembelian berdasarkan jumlah
     */
    private function getTopPembelian()
    {
        return PembelianDetail::select('id_produk', DB::raw('SUM(jumlah) as total_dibeli'))
            ->with(['produk' => function($query) {
                $query->select('id_produk', 'nama_produk', 'harga_beli');
            }])
            ->groupBy('id_produk')
            ->orderBy('total_dibeli', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'nama_produk' => $item->produk->nama_produk ?? 'Produk Tidak Ditemukan',
                    'total_dibeli' => $item->total_dibeli,
                    'harga_beli' => $item->produk->harga_beli ?? 0,
                    'total_pengeluaran' => $item->total_dibeli * ($item->produk->harga_beli ?? 0)
                ];
            });
    }

    /**
     * Get Recent Penjualan (5 transaksi terakhir)
     */
    private function getRecentPenjualan()
    {
        return Penjualan::with(['member'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id_penjualan' => $item->id_penjualan,
                    'member_nama' => $item->member->nama ?? 'Umum',
                    'total_item' => $item->total_item,
                    'total_harga' => $item->total_harga,
                    'bayar' => $item->bayar,
                    'created_at' => $item->created_at->format('d/m/Y H:i')
                ];
            });
    }

    /**
     * Get Recent Pembelian (5 transaksi terakhir)
     */
    private function getRecentPembelian()
    {
        return Pembelian::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id_pembelian' => $item->id_pembelian,
                    'total_item' => $item->total_item,
                    'total_harga' => $item->total_harga,
                    'bayar' => $item->bayar,
                    'created_at' => $item->created_at->format('d/m/Y H:i')
                ];
            });
    }

    /**
     * Get Monthly Statistics
     */
    private function getMonthlyStats()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        
        $total_penjualan_bulan = Penjualan::where('created_at', 'LIKE', "%$currentMonth%")->sum('bayar');
        $total_pembelian_bulan = Pembelian::where('created_at', 'LIKE', "%$currentMonth%")->sum('bayar');
        $total_pengeluaran_bulan = Pengeluaran::where('created_at', 'LIKE', "%$currentMonth%")->sum('nominal');
        
        $profit_bulan = $total_penjualan_bulan - $total_pembelian_bulan - $total_pengeluaran_bulan;
        
        return [
            'total_penjualan' => $total_penjualan_bulan,
            'total_pembelian' => $total_pembelian_bulan,
            'total_pengeluaran' => $total_pengeluaran_bulan,
            'profit' => $profit_bulan,
            'bulan' => Carbon::now()->format('F Y')
        ];
    }
}
