@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-flat"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                    <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')"
                        class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                    <button onclick="cetakBarcode('{{ route('produk.cetak_barcode') }}')" class="btn btn-info btn-flat"><i
                            class="fa fa-barcode"></i> Cetak Barcode</button>
                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal"
                        data-target=".bd-example-modal-lg"><i class="fa fa-file-excel-o"></i> Laporan</button>
                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Stok Produk</h5>
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
                                        onclick="openProduk(document.getElementById('awal').value, document.getElementById('akhir').value)"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <button onclick="addBackup('{{ route('produk.backup_data') }}')" class="btn btn-warning btn-flat"><i
                            class="fa fa-plus-circle"></i> Backup Produk</button> --}}
                    @if (auth()->user()->level == 6)
                    @else
                                <button type="button" class="btn btn-warning btn-flat {{ $buttonClass }}"{{ $buttonAttributes }}
                                    onclick="backupData('{{ route('produk.backup_data') }}')">
                                    <i class="fa fa-plus-circle"></i> Backup Produk {{ date('F Y') }}
                                </button>
                    @endif
                </div>
                <div class="box-body table-responsive">
                    {{-- <table width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_produk }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table> --}}
                    <form action="" method="post" class="form-produk">
                        @csrf
                        <table class="table table-stiped table-bordered">
                            <thead>
                                <th width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th>Stok</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @includeIf('produk.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                serverSide: true,
                ajax: {
                    url: '{{ route('produk.data') }}',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [{
                        data: 'select_all',
                        searchable: false,
                        sortable: false
                    },
                    {
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
                        data: 'tanggal_expire'
                    },
                    {
                        data: 'satuan'
                    },
                    {
                        data: 'harga_beli'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ],
                columnDefs: [{
                    targets: 4,
                    visible: {{ auth()->user()->level != 4 && auth()->user()->level != 5 ? 'true' : 'false' }}
                }]
            });

            $('#modal-form').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            showCreateSuccess('Data berhasil disimpan!');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            console.log('Error details:', errors);
                            if (errors.responseJSON && errors.responseJSON.message) {
                                alert('Error: ' + errors.responseJSON.message);
                            } else {
                                alert('Terjadi kesalahan saat menyimpan data. Status: ' + errors.status);
                            }
                        });
                }
            });

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            });
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Produk');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_produk]').focus();
        }

        function addBackup(url) {
            // Create a new form element
            var form = document.createElement('form');
            form.setAttribute('method', 'POST');
            form.setAttribute('action', url);

            // Create a new CSRF token input field
            var csrfInput = document.createElement('input');
            csrfInput.setAttribute('type', 'hidden');
            csrfInput.setAttribute('name', '_token');
            csrfInput.setAttribute('value', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Append the CSRF token input field to the form
            form.appendChild(csrfInput);

            // Append the form to the body (or another container element)
            document.body.appendChild(form);

            // Submit the form
            form.submit();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Produk');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama_produk]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=kode_produk]').val(response.kode_produk);
                    $('#modal-form [name=nama_produk]').val(response.nama_produk);
                    $('#modal-form [name=id_kategori]').val(response.id_kategori);
                    $('#modal-form [name=merk]').val(response.merk);
                    $('#modal-form [name=satuan]').val(response.satuan);
                    $('#modal-form [name=harga_beli]').val(response.harga_beli);
                    $('#modal-form [name=harga_jual]').val(response.harga_jual);
                    $('#modal-form [name=diskon]').val(response.diskon);
                    $('#modal-form [name=stok]').val(response.stok);
                    $('#modal-form [name=tanggal_expire]').val(response.tanggal_expire);
                })
            // .fail((errors) => {
            //     alert('Tidak dapat menampilkan data');
            //     return;
            // });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Hapus Data',
                text: 'Yakin ingin menghapus data ini?',
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
                            showDeleteSuccess('Data berhasil dihapus!');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Tidak dapat menghapus data',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }

        function deleteSelected(url) {
            if ($('input:checked').length > 1) {
                Swal.fire({
                    title: 'Hapus Data',
                    text: 'Yakin ingin menghapus data terpilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post(url, $('.form-produk').serialize())
                            .done((response) => {
                                table.ajax.reload();
                            })
                            .fail((errors) => {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Tidak dapat menghapus data',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                });
                                return;
                            });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Pilih data yang akan dihapus',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
                return;
            }
        }

        function cetakBarcode(url) {
            if ($('input:checked').length < 1) {
                alert('Pilih data yang akan dicetak');
                return;
            } else if ($('input:checked').length < 3) {
                alert('Pilih minimal 3 data untuk dicetak');
                return;
            } else {
                $('.form-produk')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        }

        function openProduk(awal, akhir) {
            window.open('/produk/stok/' + awal + '/' + akhir, 'Laporan Produk', 'width=900,height=675');
        }

        function backupData(url) {
            Swal.fire({
                title: 'Backup Data Produk',
                text: 'Yakin ingin backup data produk {{ date('F Y') }}?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                    
                    showDeleteSuccess('Backup data produk berhasil dimulai!');
                }
            });
        }
    </script>
    
    <style>
        @keyframes swal2-success-icon-animation {
            0% {
                transform: scale(0) rotate(0deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.2) rotate(180deg);
                opacity: 1;
            }
            100% {
                transform: scale(1) rotate(360deg);
                opacity: 1;
            }
        }
        
        .swal2-success .swal2-success-ring {
            animation: swal2-success-ring-animation 0.75s ease-in-out;
        }
        
        @keyframes swal2-success-ring-animation {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            40% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .swal2-success .swal2-success-fix {
            animation: swal2-success-fix-animation 0.75s ease-in-out 0.25s both;
        }
        
        @keyframes swal2-success-fix-animation {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endpush
