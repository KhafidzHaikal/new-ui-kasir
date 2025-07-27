/**
 * Script untuk mengupdate semua confirm() biasa menjadi confirmDelete() universal
 */

const fs = require('fs');
const path = require('path');

// Daftar file yang perlu diupdate
const files = [
    'resources/views/jasa/index.blade.php',
    'resources/views/kasir/index_kasir.blade.php',
    'resources/views/kategori/index.blade.php',
    'resources/views/member/index.blade.php',
    'resources/views/pembelian/index.blade.php',
    'resources/views/pembelian_detail/index.blade.php',
    'resources/views/pengeluaran/index.blade.php',
    'resources/views/penjualan/index.blade.php',
    'resources/views/penjualan_detail/index.blade.php',
    'resources/views/produk/index.blade.php',
    'resources/views/simpanan/index.blade.php',
    'resources/views/simpanan/pokok/index.blade.php',
    'resources/views/simpanan/transaksi/index.blade.php',
    'resources/views/supplier/index.blade.php',
    'resources/views/user/index.blade.php'
];

// Mapping untuk pesan yang lebih spesifik
const messageMapping = {
    'jasa': {
        title: 'Hapus Data Jasa?',
        message: 'Data jasa yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data jasa...',
        success: 'Data jasa berhasil dihapus!',
        error: 'Tidak dapat menghapus data jasa'
    },
    'kasir': {
        title: 'Hapus Data Kasir?',
        message: 'Data kasir yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data kasir...',
        success: 'Data kasir berhasil dihapus!',
        error: 'Tidak dapat menghapus data kasir'
    },
    'kategori': {
        title: 'Hapus Data Kategori?',
        message: 'Data kategori yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data kategori...',
        success: 'Data kategori berhasil dihapus!',
        error: 'Tidak dapat menghapus data kategori'
    },
    'member': {
        title: 'Hapus Data Member?',
        message: 'Data member yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data member...',
        success: 'Data member berhasil dihapus!',
        error: 'Tidak dapat menghapus data member'
    },
    'pembelian': {
        title: 'Hapus Data Pembelian?',
        message: 'Data pembelian yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data pembelian...',
        success: 'Data pembelian berhasil dihapus!',
        error: 'Tidak dapat menghapus data pembelian'
    },
    'pembelian_detail': {
        title: 'Hapus Detail Pembelian?',
        message: 'Detail pembelian yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus detail pembelian...',
        success: 'Detail pembelian berhasil dihapus!',
        error: 'Tidak dapat menghapus detail pembelian'
    },
    'pengeluaran': {
        title: 'Hapus Data Pengeluaran?',
        message: 'Data pengeluaran yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data pengeluaran...',
        success: 'Data pengeluaran berhasil dihapus!',
        error: 'Tidak dapat menghapus data pengeluaran'
    },
    'penjualan': {
        title: 'Hapus Data Penjualan?',
        message: 'Data penjualan yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data penjualan...',
        success: 'Data penjualan berhasil dihapus!',
        error: 'Tidak dapat menghapus data penjualan'
    },
    'penjualan_detail': {
        title: 'Hapus Detail Penjualan?',
        message: 'Detail penjualan yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus detail penjualan...',
        success: 'Detail penjualan berhasil dihapus!',
        error: 'Tidak dapat menghapus detail penjualan'
    },
    'produk': {
        title: 'Hapus Data Produk?',
        message: 'Data produk yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data produk...',
        success: 'Data produk berhasil dihapus!',
        error: 'Tidak dapat menghapus data produk'
    },
    'simpanan': {
        title: 'Hapus Data Simpanan?',
        message: 'Data simpanan yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data simpanan...',
        success: 'Data simpanan berhasil dihapus!',
        error: 'Tidak dapat menghapus data simpanan'
    },
    'supplier': {
        title: 'Hapus Data Supplier?',
        message: 'Data supplier yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data supplier...',
        success: 'Data supplier berhasil dihapus!',
        error: 'Tidak dapat menghapus data supplier'
    },
    'user': {
        title: 'Hapus Data User?',
        message: 'Data user yang dihapus tidak dapat dikembalikan!',
        loading: 'Menghapus data user...',
        success: 'Data user berhasil dihapus!',
        error: 'Tidak dapat menghapus data user'
    }
};

function updateFile(filePath) {
    try {
        const fullPath = path.join('/mnt/d/Program/Kasir-NEW-UI', filePath);
        let content = fs.readFileSync(fullPath, 'utf8');
        
        // Extract module name from path
        const moduleName = filePath.split('/')[2]; // resources/views/[module]/index.blade.php
        const messages = messageMapping[moduleName] || {
            title: 'Hapus Data?',
            message: 'Data yang dihapus tidak dapat dikembalikan!',
            loading: 'Menghapus data...',
            success: 'Data berhasil dihapus!',
            error: 'Tidak dapat menghapus data'
        };
        
        // Pattern untuk mencari dan mengganti confirm()
        const confirmPattern = /if\s*\(\s*confirm\s*\(\s*['"`]([^'"`]*)['"`]\s*\)\s*\)\s*\{/g;
        
        // Replace confirm dengan confirmDelete
        content = content.replace(confirmPattern, (match, message) => {
            return `confirmDelete('${messages.title}', '${messages.message}')
                .then(result => {
                    if (result.isConfirmed) {
                        showLoading('${messages.loading}');`;
        });
        
        // Update success response
        const successPattern = /table\.ajax\.reload\(\);/g;
        content = content.replace(successPattern, (match) => {
            return `hideLoading();
                                table.ajax.reload();
                                toastSuccess('${messages.success}');`;
        });
        
        // Update error response
        const errorPattern = /alert\s*\(\s*['"`]([^'"`]*)['"`]\s*\);/g;
        content = content.replace(errorPattern, (match, errorMsg) => {
            return `hideLoading();
                                alertError('Gagal!', '${messages.error}');`;
        });
        
        // Fix closing braces
        content = content.replace(/\}\s*$/gm, (match) => {
            if (content.includes('confirmDelete')) {
                return `                    }
                });
        }`;
            }
            return match;
        });
        
        fs.writeFileSync(fullPath, content, 'utf8');
        // console.log(`✅ Updated: ${filePath}`);
        
    } catch (error) {
        console.error(`❌ Error updating ${filePath}:`, error.message);
    }
}

// Update semua file
// console.log('🚀 Starting to update confirm() alerts...\n');

files.forEach(updateFile);

// console.log('\n✨ All files updated successfully!');
// console.log('\n📋 Summary of changes:');
// console.log('- confirm() → confirmDelete() with modern UI');
// console.log('- Added loading states with showLoading()/hideLoading()');
// console.log('- Added success toast notifications');
// console.log('- Added error alerts with better messaging');
// console.log('- Consistent with universal alert system design');
