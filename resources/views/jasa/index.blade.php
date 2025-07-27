@extends('layouts.master')

@section('title')
    Daftar Jasa
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Jasa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('jasa.store') }}')" class="btn btn-success btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
                        <i class="fa fa-file-excel-o"></i> Laporan
                    </button>
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Jasa</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Jasa</label>
                                            <div class="col-sm-5">
                                                <select id="jasa" class="form-control" required>
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="cuci">Jasa Cuci</option>
                                                    <option value="service">Jasa Service</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="tanggal_awal" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="tanggal_akhir" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/jasa/'+document.getElementById('jasa').value+ '/' +document.getElementById('tanggal_awal').value+ '/' +document.getElementById('tanggal_akhir').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered" id="jasa-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Deskripsi</th>
                                <th>Nominal</th>
                                <th>Pembagian (%)</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('jasa.form')
@endsection

@push('scripts')
    <!-- Enhanced Confirm Delete System -->
    <script src="{{ asset('js/enhanced-confirm-delete.js') }}"></script>

    <!-- Toastify for additional notifications -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        let table;

        $(function() {
            table = $('#jasa-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('jasa.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'persen',
                        name: 'persen'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload table when modal is closed
            $('#modal-form').on('hidden.bs.modal', function() {
                table.ajax.reload();
            });
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Jasa');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=deskripsi]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Jasa');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');

            $.get(url + '/edit')
                .done((response) => {
                    $('#modal-form [name=deskripsi]').val(response.deskripsi);
                    $('#modal-form [name=nominal]').val(response.nominal);
                    $('#modal-form [name=persen]').val(response.persen);
                })
                .fail((errors) => {
                    showToastError('Tidak dapat menampilkan data untuk diedit');
                    return;
                });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Hapus Data Jasa',
                text: 'Yakin ingin menghapus data jasa ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            showDeleteSuccess('Data jasa berhasil dihapus!');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Tidak dapat menghapus data jasa',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }

        function submitForm(originalForm) {
            // Show loading toast (minimal)
            const loadingToast = showToastLoading('Menyimpan data jasa...');

            $.post({
                    url: $(originalForm).attr('action'),
                    data: $(originalForm).serialize()
                })
                .done((response) => {
                    // Hide loading toast
                    hideAllToasts();

                    // Show simple success toast (no SweetAlert popup for save)
                    showToastSuccess('Data jasa berhasil disimpan!');

                    // Close modal and reload table
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    // Hide loading toast
                    hideAllToasts();

                    console.error('Submit error:', errors);
                    let errorMessage = 'Tidak dapat menyimpan data jasa';

                    if (errors.responseJSON && errors.responseJSON.message) {
                        errorMessage = errors.responseJSON.message;
                    } else if (errors.responseJSON && errors.responseJSON.errors) {
                        // Handle validation errors
                        let validationErrors = errors.responseJSON.errors;
                        let errorList = [];
                        for (let field in validationErrors) {
                            errorList.push(validationErrors[field][0]);
                        }
                        errorMessage = errorList.join(', ');
                    } else if (errors.status === 422) {
                        errorMessage = 'Data yang dimasukkan tidak valid';
                    } else if (errors.status === 500) {
                        errorMessage = 'Terjadi kesalahan server saat menyimpan data';
                    }

                    // Show error toast (no SweetAlert popup for save errors)
                    showToastError(errorMessage);
                });
        }

        // Toast notification helpers
        function showToastSuccess(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(135deg, #27ae60, #2ecc71)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function showToastError(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(135deg, #e74c3c, #c0392b)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function showToastInfo(message) {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: message,
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(135deg, #3498db, #2980b9)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function showToastLoading(message) {
            if (typeof Toastify !== 'undefined') {
                return Toastify({
                    text: `<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> ${message}`,
                    duration: -1,
                    close: false,
                    gravity: "top",
                    position: "center",
                    escapeMarkup: false,
                    style: {
                        background: "linear-gradient(135deg, #34495e, #2c3e50)",
                        borderRadius: "10px",
                        fontWeight: "500"
                    }
                }).showToast();
            }
        }

        function hideAllToasts() {
            if (typeof Toastify !== 'undefined') {
                document.querySelectorAll('.toastify').forEach(toast => {
                    toast.remove();
                });
            }
        }

        // Backward compatibility functions
        function alertSuccess(title, message) {
            showToastSuccess(message || title);
        }

        function alertError(title, message) {
            showToastError(message || title);
        }

        function alertWarning(title, message) {
            showToastInfo(message || title);
        }

        function alertInfo(title, message) {
            showToastInfo(message || title);
        }
    </script>
@endpush
