// Contoh update untuk resources/views/member/index.blade.php
// Ganti fungsi deleteData yang lama dengan yang baru ini:

function deleteData(url) {
    confirmDelete('Hapus Data Member?', 'Data member yang dihapus tidak dapat dikembalikan!')
        .then(result => {
            if (result.isConfirmed) {
                showLoading('Menghapus data member...');
                
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        hideLoading();
                        table.ajax.reload();
                        toastSuccess('Data member berhasil dihapus!');
                    })
                    .fail((errors) => {
                        hideLoading();
                        alertError('Gagal!', 'Tidak dapat menghapus data member');
                    });
            }
        });
}
