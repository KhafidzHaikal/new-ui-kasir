@extends('layouts.master')

@section('title')
    Daftar Simpanan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Simpanan Pokok</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-simpanan-pokok">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nama Simpanan</th>
                            <th>Nominal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let table, table1;

        $(function() {
            table = $('.table-simpanan-pokok').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('simpanan_induk.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'nominal'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
        });

        function deleteData(url) {
            Swal.fire({
                title: 'Hapus Data Simpanan Pokok',
                text: 'Yakin ingin menghapus data simpanan pokok ini?',
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
                            showDeleteSuccess('Data simpanan pokok berhasil dihapus!');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Tidak dapat menghapus data simpanan pokok',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }
    </script>
@endpush
