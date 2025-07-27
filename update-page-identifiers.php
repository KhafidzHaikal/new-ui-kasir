<?php
/**
 * Script untuk menambahkan page identifier ke semua view files
 * Jalankan dengan: php update-page-identifiers.php
 */

// Mapping page identifiers
$pageIdentifiers = [
    // Admin
    'admin/dashboard.blade.php' => 'dashboard',
    
    // Master Data
    'kategori/index.blade.php' => 'kategori',
    'kategori/form.blade.php' => 'kategori',
    'produk/index.blade.php' => 'produk',
    'produk/form.blade.php' => 'produk',
    'produk/barcode.blade.php' => 'produk',
    'member/index.blade.php' => 'member',
    'member/form.blade.php' => 'member',
    'supplier/index.blade.php' => 'supplier',
    'supplier/form.blade.php' => 'supplier',
    
    // Transaksi
    'pengeluaran/index.blade.php' => 'pengeluaran',
    'pengeluaran/form.blade.php' => 'pengeluaran',
    'pembelian/index.blade.php' => 'pembelian',
    'pembelian/form.blade.php' => 'pembelian',
    'pembelian_detail/index.blade.php' => 'pembelian',
    'penjualan/index.blade.php' => 'penjualan',
    'penjualan/form.blade.php' => 'penjualan',
    'penjualan_detail/index.blade.php' => 'penjualan',
    
    // Jasa & USP
    'jasa/index.blade.php' => 'jasa',
    'jasa/form.blade.php' => 'jasa',
    'simpanan/index.blade.php' => 'simpanan',
    'simpanan/form.blade.php' => 'simpanan',
    'pinjaman/index.blade.php' => 'pinjaman',
    'pinjaman/form.blade.php' => 'pinjaman',
    'pengambilan/index.blade.php' => 'pengambilan',
    'pengambilan/form.blade.php' => 'pengambilan',
    
    // Laporan
    'laporan/index.blade.php' => 'laporan',
    'laporan/kasir.blade.php' => 'laporan',
    
    // System
    'user/index.blade.php' => 'user',
    'user/form.blade.php' => 'user',
    'user/profil.blade.php' => 'user',
    'setting/index.blade.php' => 'setting',
    'setting/form.blade.php' => 'setting',
    
    // Kasir
    'kasir/dashboard.blade.php' => 'dashboard',
];

$viewsPath = __DIR__ . '/resources/views/';
$updatedFiles = [];
$errors = [];

foreach ($pageIdentifiers as $filePath => $identifier) {
    $fullPath = $viewsPath . $filePath;
    
    if (!file_exists($fullPath)) {
        $errors[] = "File tidak ditemukan: $filePath";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    // Check if already has page-identifier
    if (strpos($content, "@section('page-identifier')") !== false) {
        echo "✅ $filePath sudah memiliki page-identifier\n";
        continue;
    }
    
    // Find @section('title') and add page-identifier after it
    $pattern = "/(@section\('title'\).*?@endsection)/s";
    
    if (preg_match($pattern, $content, $matches)) {
        $titleSection = $matches[1];
        $newContent = $titleSection . "\n\n@section('page-identifier')\n    $identifier\n@endsection";
        
        $updatedContent = str_replace($titleSection, $newContent, $content);
        
        if (file_put_contents($fullPath, $updatedContent)) {
            $updatedFiles[] = $filePath;
            echo "✅ Updated: $filePath -> $identifier\n";
        } else {
            $errors[] = "Gagal menulis file: $filePath";
        }
    } else {
        $errors[] = "Tidak ditemukan @section('title') di: $filePath";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "SUMMARY:\n";
echo "✅ Files updated: " . count($updatedFiles) . "\n";
echo "❌ Errors: " . count($errors) . "\n";

if (!empty($errors)) {
    echo "\nERRORS:\n";
    foreach ($errors as $error) {
        echo "❌ $error\n";
    }
}

if (!empty($updatedFiles)) {
    echo "\nUPDATED FILES:\n";
    foreach ($updatedFiles as $file) {
        echo "✅ $file\n";
    }
}

echo "\n🎉 Page identifiers update completed!\n";
echo "Sekarang setiap page memiliki identifier yang jelas untuk identifikasi.\n";
?>
