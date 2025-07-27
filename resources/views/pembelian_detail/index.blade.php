@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-pembelian tbody tr:last-child {
            display: none;
        }

        /* Panel Styling */
        .panel {
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .panel-body {
            border: 1px solid #ddd;
            border-top: none;
        }
        
        /* Enhanced Form Styling */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            margin-bottom: 8px;
            display: block;
        }
        
        /* Responsive Design */
        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 2.5em !important;
                height: 60px !important;
                padding-top: 8px !important;
            }
            
            .panel-header h4 {
                font-size: 16px !important;
            }
            
            .col-lg-8, .col-lg-4 {
                margin-bottom: 15px;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
            
            .input-group {
                width: 100%;
            }
            
            .input-group .form-control {
                border-radius: 4px 0 0 4px;
            }
            
            .input-group-btn .btn {
                border-radius: 0 4px 4px 0;
                height: 34px;
            }
        }
        
        @media(max-width: 480px) {
            .tampil-bayar {
                font-size: 2em !important;
                height: 50px !important;
                padding-top: 5px !important;
            }
            
            .panel-body {
                padding: 15px !important;
            }
        }
        
        .input-group {
            display: flex;
            width: 100%;
        }
        
        .input-group .form-control {
            flex: 1;
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Supplier</td>
                            <td>: {{ $supplier->nama }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>: {{ $supplier->telepon }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $supplier->alamat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">

                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2 col-md-3 col-sm-12">Kode Produk</label>
                        <div class="col-lg-5 col-md-9 col-sm-12">
                            <form class="form-produk" style="display: none;">
                                @csrf
                                <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                            </form>
                            <form id="search-form">
                                <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                                <div class="input-group">
                                    <input type="text" name="kode_produk" id="kode_produk" class="form-control" 
                                           placeholder="Masukkan kode produk" autofocus>
                                    <span class="input-group-btn">
                                        <button onclick="tampilProduk()" class="btn btn-info btn-flat" style="width: 50px; height:50px"ype="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-stiped table-bordered table-pembelian">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-lg-8 col-md-7">
                            <!-- Modern Display Card -->
                            <div class="panel panel-primary" style="margin-bottom: 15px;">
                                <div class="panel-header" style="background: #605ca8; color: white; padding: 10px 15px; border-radius: 4px 4px 0 0;">
                                    <h4 style="margin: 0; font-weight: 600;"><i class="fa fa-calculator"></i> Total Pembayaran</h4>
                                </div>
                                <div class="panel-body" style="background: #f9f9f9; padding: 0; border-radius: 0 0 4px 4px;">
                                    <div class="tampil-bayar" style="background: #605ca8; color: white; margin: 0; border-radius: 0;"></div>
                                    <div class="tampil-terbilang" style="background: #ecf0f5; border-top: 1px solid #ddd; font-style: italic; color: #666;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5">
                            <!-- Form Panel -->
                            <div class="panel panel-default">
                                <div class="panel-header" style="background: #605ca8; color: white; padding: 10px 15px; border-bottom: 1px solid #ddd; border-radius: 4px 4px 0 0;">
                                    <h4 style="margin: 0; color: white; font-weight: 600;"><i class="fa fa-edit"></i> Detail Transaksi</h4>
                                </div>
                                <div class="panel-body" style="padding: 20px; background: white; border-radius: 0 0 4px 4px;">
                                    <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                                        @csrf
                                        <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                                        <input type="hidden" name="total" id="total">
                                        <input type="hidden" name="total_item" id="total_item">
                                        <input type="hidden" name="bayar" id="bayar">

                                        <div class="form-group">
                                            <label class="control-label" style="font-weight: 600; color: #555;">Tanggal Pembelian</label>
                                            <input type="date" class="form-control" id="created_at" name="created_at"
                                                required value={{ $date }} style="border-radius: 4px; border: 1px solid #ddd;">
                                        </div>
                                        <div class="form-group">
                                            <label for="totalrp" class="control-label" style="font-weight: 600; color: #555;">Total</label>
                                            <input type="text" id="totalrp" class="form-control" readonly 
                                                style="background: #f9f9f9; border-radius: 4px; border: 1px solid #ddd;">
                                        </div>
                                        <div class="form-group">
                                            <label for="diskon" class="control-label" style="font-weight: 600; color: #555;">Diskon (%)</label>
                                            <input type="number" name="diskon" id="diskon" class="form-control"
                                                value="{{ $diskon }}" style="border-radius: 4px; border: 1px solid #ddd;">
                                        </div>
                                        <div class="form-group">
                                            <label for="bayar" class="control-label" style="font-weight: 600; color: #555;">Total Bayar</label>
                                            <input type="text" id="bayarrp" class="form-control" readonly
                                                style="background: #f9f9f9; border-radius: 4px; border: 1px solid #ddd; font-weight: 600; color: #2c5aa0;">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(document).ready(function() {
            $('#search-form').submit(function(event) {
                event.preventDefault();
                
                var kode_produk = $('#kode_produk').val().trim();
                if (kode_produk === '') {
                    $('#kode_produk').focus();
                    return;
                }
                
                var formData = $(this).serialize();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('search-pembelian') }}",
                    data: formData,
                    success: function(response) {
                        if (response.success == 200) {
                            $('#kode_produk').val("");
                            $('#kode_produk').focus();
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Produk Tidak Ditemukan');
                        $('#kode_produk').val("");
                        $('#kode_produk').focus();
                        return;
                    }
                });
            });
        });

        $(function() {
            $('body').addClass('sidebar-collapse');

            table = $('.table-pembelian').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
                    },
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
                            data: 'harga_beli'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'subtotal'
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm($('#diskon').val());
                });
            table2 = $('.table-produk').DataTable();

            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                if (jumlah < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }
                if (jumlah > 10000) {
                    $(this).val(10000);
                    alert('Jumlah tidak boleh lebih dari 10000');
                    return;
                }

                $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        });
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('.btn-simpan').on('click', function() {
                $('.form-pembelian').submit();
            });
        });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').val('');
                    $('#kode_produk').focus();
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Hapus Item',
                text: 'Yakin ingin menghapus item ini?',
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
                            showDeleteSuccess('Item berhasil dihapus!');
                            $('#kode_produk').val('');
                            $('#kode_produk').focus();
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        })
                        .fail((errors) => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Tidak dapat menghapus item',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }

        function loadForm(diskon = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
