@extends('layouts.master')

@section('title')
    Daftar Simpanan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Simpanan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal"
                        data-target=".bd-example-modal-lg"><i class="fa fa-file-excel-o"></i> Laporan</button>
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Simpanan</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="awal" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="akhir" required
                                                    value="{{ request('awal') ?? date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/penjualan/' +document.getElementById('awal').value+ '/' +document.getElementById('akhir').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('simpanan.create') }}" class="btn btn-primary btn-flat">Transkasi Simpanan</a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-simpanan">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode Member</th>
                            <th>Nama Member</th>
                            <th>Pokok</th>
                            <th>Wajib</th>
                            <th>MNSUKA</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('simpanan.detail')
@endsection

@push('scripts')
    <script>
        let table, table1;

        $(function() {
            table = $('.table-simpanan').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('simpanan.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'kode_member'
                    },
                    {
                        data: 'nama_member'
                    },
                    {
                        data: 'bayar_pokok'
                    },
                    {
                        data: 'bayar_wajib'
                    },
                    {
                        data: 'bayar_manasuka'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            table1 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'subtotal'
                    },
                ]
            })
        });

        function showDetail(url) {
            $('#modal-detail').modal('show');

            table1.ajax.url(url);
            table1.ajax.reload();
        }

        

        function deleteData(url) {
            Swal.fire({
                title: 'Hapus Data Simpanan',
                text: 'Yakin ingin menghapus data simpanan ini?',
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
                            showDeleteSuccess('Data simpanan berhasil dihapus!');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Tidak dapat menghapus data simpanan',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }
    </script>
@endpush
