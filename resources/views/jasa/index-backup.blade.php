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
                    <button onclick="addForm('{{ route('jasa.store') }}')" class="btn btn-success btn-flat"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg"><i
                            class="fa fa-file-excel-o"></i> Laporan</button>
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Jasa</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
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
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-primary" onclick="cetakLaporan()">Cetak</button>
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
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('jasa.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#jasa-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route('jasa.data') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                        width: '5%'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        searchable: false,
                        orderable: true,
                        className: 'text-right'
                    },
                    {
                        data: 'persen',
                        name: 'persen',
                        searchable: false,
                        orderable: true,
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        searchable: false,
                        orderable: false,
                        width: '15%',
                        className: 'text-center'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ]
            });

            // Reload table
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
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }

        function submitForm(originalForm) {
            $.post({
                    url: $(originalForm).attr('action'),
                    data: $(originalForm).serialize()
                })
                .done((response) => {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        function cetakLaporan() {
            let jasa = $('#jasa').val();
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            if (jasa && tanggal_awal && tanggal_akhir) {
                window.open(`{{ url('/jasa') }}/${jasa}/${tanggal_awal}/${tanggal_akhir}`, '_blank');
            } else {
                alert('Harap lengkapi semua field');
            }
        }
    </script>
@endpush
