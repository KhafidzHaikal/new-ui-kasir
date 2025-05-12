<?php

namespace App\Console\Commands;

use App\Models\BackupProduk;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DBProduk extends Command
{
    protected $signature = 'db:produk';
    protected $description = 'Backup produk data on the first day of each month';

    public function handle()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        // 🔁 First backup run - end of previous month
        $results = DB::table('produk')
            ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
            ->join(DB::raw('(
                SELECT 
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    id_produk,
                    COALESCE(SUM(jumlah), 0) as total_jumlah
                FROM pembelian_detail
                WHERE DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
                GROUP BY month, id_produk
                UNION ALL
                SELECT "' . $currentMonth . '" as month, id_produk, 0 as total_jumlah
                FROM produk
                WHERE NOT EXISTS (
                    SELECT 1 FROM pembelian_detail
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

        foreach ($results as $result) {
            BackupProduk::create([
                'id_produk' => $result->id_produk,
                'id_kategori' => $result->id_kategori,
                'nama_produk' => $result->nama_produk,
                'satuan' => $result->satuan,
                'harga_beli' => $result->harga_beli,
                'stok_awal' => $result->stok_lama,
                'stok_akhir' => $result->stok,
                'stok_belanja' => $result->total_jumlah,
                'total_belanja' => $result->harga_beli * $result->total_jumlah,
                'tanggal_expire' => $result->tanggal_expire,
                'created_at' => Carbon::now()->startOfMonth()->subMonth()->endOfMonth(),
                'updated_at' => Carbon::now()->startOfMonth()->subMonth()->endOfMonth(),
            ]);
        }

        // ✅ Update stok_lama after first backup
        DB::table('produk')->update(['stok_lama' => DB::raw('stok')]);

        // 🔁 Second backup run - first day of current month
        $hasil = DB::table('produk')
            ->leftJoin('pembelian_detail', 'produk.id_produk', '=', 'pembelian_detail.id_produk')
            ->join(DB::raw('(
                SELECT 
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    id_produk,
                    COALESCE(SUM(jumlah), 0) as total_jumlah
                FROM pembelian_detail
                WHERE DATE_FORMAT(created_at, "%Y-%m") = "' . $currentMonth . '"
                GROUP BY month, id_produk
                UNION ALL
                SELECT "' . $currentMonth . '" as month, id_produk, 0 as total_jumlah
                FROM produk
                WHERE NOT EXISTS (
                    SELECT 1 FROM pembelian_detail
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

        foreach ($hasil as $result) {
            BackupProduk::create([
                'id_produk' => $result->id_produk,
                'id_kategori' => $result->id_kategori,
                'nama_produk' => $result->nama_produk,
                'satuan' => $result->satuan,
                'harga_beli' => $result->harga_beli,
                'stok_awal' => $result->stok_lama,
                'stok_akhir' => $result->stok,
                'stok_belanja' => $result->total_jumlah,
                'total_belanja' => $result->harga_beli * $result->total_jumlah,
                'tanggal_expire' => $result->tanggal_expire,
                'created_at' => Carbon::now()->startOfMonth(),
                'updated_at' => Carbon::now()->startOfMonth(),
            ]);
        }

        $this->info("Backup created for both end-of-last-month and start-of-this-month for {$currentMonth}");
    }
}
