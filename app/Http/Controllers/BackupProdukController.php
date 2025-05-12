<?php

namespace App\Http\Controllers;

use App\Models\BackupProduk;
use App\Models\PembelianDetail;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BackupProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storebackup_produkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $currentMonth = date('Y-m');
        $produk = Produk::where('id_kategori', 5)->update(['created_at' => now()], ['deleted_at' => now()]);
        $twoMonthsAhead = date('Y-m', strtotime("$currentMonth + 2 months"));

        if (auth()->user()->level == 4) {
            $results = DB::table('produk')
                ->where('produk.id_kategori', 4)
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                    $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                })
                ->select(
                    'produk.id_produk',
                    'produk.id_kategori',
                    'produk.nama_produk',
                    'produk.satuan',
                    'produk.stok',
                    'produk.harga_beli',
                    'produk.stok_lama',
                    'produk.tanggal_expire',
                    'monthly_totals.month',
                    'monthly_totals.total_jumlah as total_jumlah'
                )
                ->where('monthly_totals.month', '=', $currentMonth)
                ->groupBy('produk.id_produk', 'monthly_totals.month')
                ->get();

            $results->map(function ($results) {
                $backup = new BackupProduk();
                $backup->id_produk = $results->id_produk;
                $backup->id_kategori = $results->id_kategori;
                $backup->nama_produk = $results->nama_produk;
                $backup->satuan = $results->satuan;
                $backup->harga_beli = $results->harga_beli;
                $backup->stok_awal = $results->stok_lama;
                $backup->stok_akhir = $results->stok;
                $backup->stok_belanja = $results->total_jumlah;
                $backup->total_belanja = $results->harga_beli * $results->total_jumlah;
                $backup->tanggal_expire = $results->tanggal_expire;
                $backup->created_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                $backup->updated_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                $backup->save();
            });

            DB::table('produk')->where('id_kategori', 4)->update(['stok_lama' => DB::raw('stok')]);

            $hasil = DB::table('produk')
                ->where('produk.id_kategori', 4)
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                    $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                })
                ->select(
                    'produk.id_produk',
                    'produk.id_kategori',
                    'produk.nama_produk',
                    'produk.satuan',
                    'produk.stok',
                    'produk.harga_beli',
                    'produk.stok_lama',
                    'produk.tanggal_expire',
                    'monthly_totals.month',
                    'monthly_totals.total_jumlah as total_jumlah'
                )
                ->where('monthly_totals.month', '=', $currentMonth)
                ->groupBy('produk.id_produk', 'monthly_totals.month')
                ->get();

            $hasil->map(function ($hasil) {
                $backup = new BackupProduk();
                $backup->id_produk = $hasil->id_produk;
                $backup->id_kategori = $hasil->id_kategori;
                $backup->nama_produk = $hasil->nama_produk;
                $backup->satuan = $hasil->satuan;
                $backup->harga_beli = $hasil->harga_beli;
                $backup->stok_awal = $hasil->stok_lama;
                $backup->stok_akhir = $hasil->stok;
                $backup->stok_belanja = $hasil->total_jumlah;
                $backup->total_belanja = $hasil->harga_beli * $hasil->total_jumlah;
                $backup->tanggal_expire = $hasil->tanggal_expire;
                $backup->created_at = Carbon::now()->startOfMonth();
                $backup->updated_at = Carbon::now()->startOfMonth();
                $backup->save();
            });
        } elseif (auth()->user()->level == 5) {
            $results = DB::table('produk')
                ->where('produk.id_kategori', 5)
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
            COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                        $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                    })
                    ->select(
                        'produk.id_produk',
                        'produk.id_kategori',
                        'produk.nama_produk',
                        'produk.satuan',
                        'produk.stok',
                        'produk.harga_beli',
                        'produk.stok_lama',
                        'produk.tanggal_expire',
                        'monthly_totals.month',
                        'monthly_totals.total_jumlah as total_jumlah'
                    )
                    ->where('monthly_totals.month', '=', $currentMonth)
                    ->groupBy('produk.id_produk', 'monthly_totals.month')
                    ->get();

                $results->map(function ($results) {
                    $backup = new BackupProduk();
                    $backup->id_produk = $results->id_produk;
                    $backup->id_kategori = $results->id_kategori;
                    $backup->nama_produk = $results->nama_produk;
                    $backup->satuan = $results->satuan;
                    $backup->harga_beli = $results->harga_beli;
                    $backup->stok_awal = $results->stok_lama;
                    $backup->stok_akhir = $results->stok;
                    $backup->stok_belanja = $results->total_jumlah;
                    $backup->total_belanja = $results->harga_beli * $results->total_jumlah;
                    $backup->tanggal_expire = $results->tanggal_expire;
                    $backup->created_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                    $backup->updated_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                    $backup->save();
                });

                DB::table('produk')->where('id_kategori', 5)->update(['stok_lama' => DB::raw('stok')]);

                $hasil = DB::table('produk')
                    ->where('produk.id_kategori', 5)
                    ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                    ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                        $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                    })
                    ->select(
                        'produk.id_produk',
                        'produk.id_kategori',
                        'produk.nama_produk',
                        'produk.satuan',
                        'produk.stok',
                        'produk.harga_beli',
                        'produk.stok_lama',
                        'produk.tanggal_expire',
                        'monthly_totals.month',
                        'monthly_totals.total_jumlah as total_jumlah'
                    )
                    ->where('monthly_totals.month', '=', $currentMonth)
                    ->groupBy('produk.id_produk', 'monthly_totals.month')
                    ->get();

                $hasil->map(function ($hasil) {
                    $backup = new BackupProduk();
                    $backup->id_produk = $hasil->id_produk;
                    $backup->id_kategori = $hasil->id_kategori;
                    $backup->nama_produk = $hasil->nama_produk;
                    $backup->satuan = $hasil->satuan;
                    $backup->harga_beli = $hasil->harga_beli;
                    $backup->stok_awal = $hasil->stok_lama;
                    $backup->stok_akhir = $hasil->stok;
                    $backup->stok_belanja = $hasil->total_jumlah;
                    $backup->total_belanja = $hasil->harga_beli * $hasil->total_jumlah;
                    $backup->tanggal_expire = $hasil->tanggal_expire;
                    $backup->created_at = Carbon::now()->startOfMonth();
                    $backup->updated_at = Carbon::now()->startOfMonth();
                    $backup->save();
                });
        } elseif (auth()->user()->level == 8) {
            $results = DB::table('produk')
                ->where('produk.id_kategori', 13)
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
                SELECT 
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                        $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                    })
                    ->select(
                        'produk.id_produk',
                        'produk.id_kategori',
                        'produk.nama_produk',
                        'produk.satuan',
                        'produk.stok',
                        'produk.harga_beli',
                        'produk.stok_lama',
                        'produk.tanggal_expire',
                        'monthly_totals.month',
                        'monthly_totals.total_jumlah as total_jumlah'
                    )
                    ->where('monthly_totals.month', '=', $currentMonth)
                    ->groupBy('produk.id_produk', 'monthly_totals.month')
                    ->get();

                $results->map(function ($results) {
                    $backup = new BackupProduk();
                    $backup->id_produk = $results->id_produk;
                    $backup->id_kategori = $results->id_kategori;
                    $backup->nama_produk = $results->nama_produk;
                    $backup->satuan = $results->satuan;
                    $backup->harga_beli = $results->harga_beli;
                    $backup->stok_awal = $results->stok_lama;
                    $backup->stok_akhir = $results->stok;
                    $backup->stok_belanja = $results->total_jumlah;
                    $backup->total_belanja = $results->harga_beli * $results->total_jumlah;
                    $backup->tanggal_expire = $results->tanggal_expire;
                    $backup->created_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                    $backup->updated_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                    $backup->save();
                });

                DB::table('produk')->where('id_kategori', 13)->update(['stok_lama' => DB::raw('stok')]);

                $hasil = DB::table('produk')
                    ->where('produk.id_kategori', 13)
                    ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                    ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                        $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                    })
                    ->select(
                        'produk.id_produk',
                        'produk.id_kategori',
                        'produk.nama_produk',
                        'produk.satuan',
                        'produk.stok',
                        'produk.harga_beli',
                        'produk.stok_lama',
                        'produk.tanggal_expire',
                        'monthly_totals.month',
                        'monthly_totals.total_jumlah as total_jumlah'
                    )
                    ->where('monthly_totals.month', '=', $currentMonth)
                    ->groupBy('produk.id_produk', 'monthly_totals.month')
                    ->get();

                $hasil->map(function ($hasil) {
                    $backup = new BackupProduk();
                    $backup->id_produk = $hasil->id_produk;
                    $backup->id_kategori = $hasil->id_kategori;
                    $backup->nama_produk = $hasil->nama_produk;
                    $backup->satuan = $hasil->satuan;
                    $backup->harga_beli = $hasil->harga_beli;
                    $backup->stok_awal = $hasil->stok_lama;
                    $backup->stok_akhir = $hasil->stok;
                    $backup->stok_belanja = $hasil->total_jumlah;
                    $backup->total_belanja = $hasil->harga_beli * $hasil->total_jumlah;
                    $backup->tanggal_expire = $hasil->tanggal_expire;
                    $backup->created_at = Carbon::now()->startOfMonth();
                    $backup->updated_at = Carbon::now()->startOfMonth();
                    $backup->save();
                });
        } elseif (auth()->user()->level == 1) {
            // DB::table('produk')->update([
            //     'created_at' => '2025-01-01 00:00:00',
            //     'updated_at' => '2025-01-01 00:00:00',
            // ]);
            $results = DB::table('produk')
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                    $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                })
                ->select(
                    'produk.id_produk',
                    'produk.id_kategori',
                    'produk.nama_produk',
                    'produk.satuan',
                    'produk.stok',
                    'produk.harga_beli',
                    'produk.stok_lama',
                    'produk.tanggal_expire',
                    'monthly_totals.month',
                    'monthly_totals.total_jumlah as total_jumlah'
                )
                ->where('monthly_totals.month', '=', $currentMonth)
                ->groupBy('produk.id_produk', 'monthly_totals.month')
                ->get();

            $results->map(function ($results) {
                $backup = new BackupProduk();
                $backup->id_produk = $results->id_produk;
                $backup->id_kategori = $results->id_kategori;
                $backup->nama_produk = $results->nama_produk;
                $backup->satuan = $results->satuan;
                $backup->harga_beli = $results->harga_beli;
                $backup->stok_awal = $results->stok_lama;
                $backup->stok_akhir = $results->stok;
                $backup->stok_belanja = $results->total_jumlah;
                $backup->total_belanja = $results->harga_beli * $results->total_jumlah;
                $backup->tanggal_expire = $results->tanggal_expire;
                $backup->created_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                $backup->updated_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                $backup->save();
            });

            DB::table('produk')->update(['stok_lama' => DB::raw('stok')]);

            $hasil = DB::table('produk')
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                id_produk,
                COALESCE(SUM(jumlah), 0) as total_jumlah
            FROM 
                pembelian_detail
            WHERE 
                DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
            GROUP BY 
                month, id_produk
            UNION ALL
            SELECT 
                "' . $currentMonth . '" as month,
                id_produk,
                0 as total_jumlah
            FROM 
                produk
            WHERE 
                NOT EXISTS (
                    SELECT 1
                    FROM pembelian_detail
                    WHERE pembelian_detail.id_produk = produk.id_produk
                    AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
                )
            ) as monthly_totals'), function ($join) {
                    $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                })
                ->select(
                    'produk.id_produk',
                    'produk.id_kategori',
                    'produk.nama_produk',
                    'produk.satuan',
                    'produk.stok',
                    'produk.harga_beli',
                    'produk.stok_lama',
                    'produk.tanggal_expire',
                    'monthly_totals.month',
                    'monthly_totals.total_jumlah as total_jumlah'
                )
                ->where('monthly_totals.month', '=', $currentMonth)
                ->groupBy('produk.id_produk', 'monthly_totals.month')
                ->get();

            $hasil->map(function ($hasil) {
                $backup = new BackupProduk();
                $backup->id_produk = $hasil->id_produk;
                $backup->id_kategori = $hasil->id_kategori;
                $backup->nama_produk = $hasil->nama_produk;
                $backup->satuan = $hasil->satuan;
                $backup->harga_beli = $hasil->harga_beli;
                $backup->stok_awal = $hasil->stok_lama;
                $backup->stok_akhir = $hasil->stok;
                $backup->stok_belanja = $hasil->total_jumlah;
                $backup->total_belanja = $hasil->harga_beli * $hasil->total_jumlah;
                $backup->tanggal_expire = $hasil->tanggal_expire;
                $backup->created_at = Carbon::now()->startOfMonth();
                $backup->updated_at = Carbon::now()->startOfMonth();
                $backup->save();
            });
        } else {
            $results = DB::table('produk')
                ->where([['produk.id_kategori', '!=', 4], ['produk.id_kategori', '!=', 5]])
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
        SELECT 
            DATE_FORMAT(created_at, "%Y-%m") as month,
            id_produk,
            COALESCE(SUM(jumlah), 0) as total_jumlah
        FROM 
            pembelian_detail
        WHERE 
            DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
        GROUP BY 
            month, id_produk
        UNION ALL
        SELECT 
            "' . $currentMonth . '" as month,
            id_produk,
            0 as total_jumlah
        FROM 
            produk
        WHERE 
            NOT EXISTS (
                SELECT 1
                FROM pembelian_detail
                WHERE pembelian_detail.id_produk = produk.id_produk
                AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
            )
        ) as monthly_totals'), function ($join) {
                    $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                })
                ->select(
                    'produk.id_produk',
                    'produk.id_kategori',
                    'produk.nama_produk',
                    'produk.satuan',
                    'produk.stok',
                    'produk.harga_beli',
                    'produk.stok_lama',
                    'produk.tanggal_expire',
                    'monthly_totals.month',
                    'monthly_totals.total_jumlah as total_jumlah'
                )
                ->where('monthly_totals.month', '=', $currentMonth)
                ->groupBy('produk.id_produk', 'monthly_totals.month')
                ->get();

            $results->map(function ($results) {
                $backup = new BackupProduk();
                $backup->id_produk = $results->id_produk;
                $backup->id_kategori = $results->id_kategori;
                $backup->nama_produk = $results->nama_produk;
                $backup->satuan = $results->satuan;
                $backup->harga_beli = $results->harga_beli;
                $backup->stok_awal = $results->stok_lama;
                $backup->stok_akhir = $results->stok;
                $backup->stok_belanja = $results->total_jumlah;
                $backup->total_belanja = $results->harga_beli * $results->total_jumlah;
                $backup->tanggal_expire = $results->tanggal_expire;
                $backup->created_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                $backup->updated_at = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                $backup->save();
            });

            DB::table('produk')->where([['id_kategori', '!=', 4], ['id_kategori', '!=', 5]])->update(['stok_lama' => DB::raw('stok')]);

            $hasil = DB::table('produk')
                ->where([['produk.id_kategori', '!=', 4], ['produk.id_kategori', '!=', 5]])
                ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
                ->join(DB::raw('(
        SELECT 
            DATE_FORMAT(created_at, "%Y-%m") as month,
            id_produk,
            COALESCE(SUM(jumlah), 0) as total_jumlah
        FROM 
            pembelian_detail
        WHERE 
            DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
        GROUP BY 
            month, id_produk
        UNION ALL
        SELECT 
            "' . $currentMonth . '" as month,
            id_produk,
            0 as total_jumlah
        FROM 
            produk
        WHERE 
            NOT EXISTS (
                SELECT 1
                FROM pembelian_detail
                WHERE pembelian_detail.id_produk = produk.id_produk
                AND DATE_FORMAT(pembelian_detail.created_at, "%Y-%m") = "' . $currentMonth . '"
            )
        ) as monthly_totals'), function ($join) {
                    $join->on('produk.id_produk', '=', 'monthly_totals.id_produk');
                })
                ->select(
                    'produk.id_produk',
                    'produk.id_kategori',
                    'produk.nama_produk',
                    'produk.satuan',
                    'produk.stok',
                    'produk.harga_beli',
                    'produk.stok_lama',
                    'produk.tanggal_expire',
                    'monthly_totals.month',
                    'monthly_totals.total_jumlah as total_jumlah'
                )
                ->where('monthly_totals.month', '=', $currentMonth)
                ->groupBy('produk.id_produk', 'monthly_totals.month')
                ->get();

            $hasil->map(function ($hasil) {
                $backup = new BackupProduk();
                $backup->id_produk = $hasil->id_produk;
                $backup->id_kategori = $hasil->id_kategori;
                $backup->nama_produk = $hasil->nama_produk;
                $backup->satuan = $hasil->satuan;
                $backup->harga_beli = $hasil->harga_beli;
                $backup->stok_awal = $hasil->stok_lama;
                $backup->stok_akhir = $hasil->stok;
                $backup->stok_belanja = $hasil->total_jumlah;
                $backup->total_belanja = $hasil->harga_beli * $hasil->total_jumlah;
                $backup->tanggal_expire = $hasil->tanggal_expire;
                $backup->created_at = Carbon::now()->startOfMonth();
                $backup->updated_at = Carbon::now()->startOfMonth();
                $backup->save();
            });
        }
        return redirect()->back()->withToast('success', 'Data Berhasil di Backup');
    }
}
