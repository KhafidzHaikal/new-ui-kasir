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
<script>
let table;

$(function() {
    table = $('#jasa-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('jasa.data') }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'deskripsi', name: 'deskripsi'},
            {data: 'nominal', name: 'nominal'},
            {data: 'persen', name: 'persen'},
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
        ]
    });
});

function addForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah Jasa');
    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
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
    });
}
</script>
@endpush
