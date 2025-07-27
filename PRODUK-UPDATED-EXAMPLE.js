// Contoh update untuk resources/views/produk/index.blade.php
// Ganti fungsi deleteData yang lama dengan yang baru ini:

function deleteData(url) {
    confirmDelete('Hapus Data Produk?', 'Data produk yang dihapus tidak dapat dikembalikan!')
        .then(result => {
            if (result.isConfirmed) {
                showLoading('Menghapus data produk...');
                
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        hideLoading();
                        table.ajax.reload();
                        toastSuccess('Data produk berhasil dihapus!');
                    })
                    .fail((errors) => {
                        hideLoading();
                        alertError('Gagal!', 'Tidak dapat menghapus data produk');
                    });
            }
        });
}
