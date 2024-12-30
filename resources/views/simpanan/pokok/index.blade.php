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
    </script>
@endpush
