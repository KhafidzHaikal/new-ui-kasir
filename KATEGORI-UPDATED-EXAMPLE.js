// Contoh update untuk resources/views/kategori/index.blade.php
// Ganti fungsi deleteData yang lama dengan yang baru ini:

function deleteData(url) {
    confirmDelete('Hapus Data Kategori?', 'Data kategori yang dihapus tidak dapat dikembalikan!')
        .then(result => {
            if (result.isConfirmed) {
                showLoading('Menghapus data kategori...');
                
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        hideLoading();
                        table.ajax.reload();
                        toastSuccess('Data kategori berhasil dihapus!');
                    })
                    .fail((errors) => {
                        hideLoading();
                        alertError('Gagal!', 'Tidak dapat menghapus data kategori');
                    });
            }
        });
}
