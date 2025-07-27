@extends('layouts.master')

@section('title')
    Transaksi Penjualan
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

        .table-penjualan tbody tr:last-child {
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
        
        /* Remove custom dropdown arrow */
        select.form-control {
            background-image: none !important;
            appearance: auto !important;
            -webkit-appearance: menulist !important;
            -moz-appearance: menulist !important;
        }
        
        /* Responsive Search Form */
        .search-input-group {
            display: flex;
            width: 100%;
        }
        
        .search-btn {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }
        
        @media(max-width: 768px) {
            .search-btn {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }
            
            .search-input-group .form-control {
                font-size: 14px;
                height: 40px;
            }
        }
        
        @media(max-width: 480px) {
            .search-btn {
                width: 35px;
                height: 35px;
                font-size: 12px;
            }
            
            .search-input-group .form-control {
                font-size: 13px;
                height: 35px;
            }
            
            .form-group.row {
                margin-bottom: 10px;
            }
            
            .form-group.row label {
                font-size: 14px;
                margin-bottom: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Penjaualn</li>
@endsection

@section('content')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">

                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2 col-md-3 col-sm-12">Kode Produk</label>
                        <div class="col-lg-5 col-md-9 col-sm-12">
                            <form class="form-produk" style="display: none;">
                                @csrf
                                <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                            </form>
                            <form id="search-form" style="width: 90%" >
                                <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                <div class="input-group search-input-group">
                                    <input type="text" name="kode_produk" id="kode_produk" class="form-control"
                                           placeholder="Masukkan kode produk" autofocus>
                                    <span class="input-group-btn">
                                        <button onclick="tampilProduk()" class="btn btn-info btn-flat search-btn" style="width: 50px; height: 50px" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-stiped table-bordered table-penjualan">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Diskon</th>
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
                                    <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                                        @csrf
                                        <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                        <input type="hidden" name="total" id="total">
                                        <input type="hidden" name="total_item" id="total_item">
                                        <input type="hidden" name="bayar" id="bayar">
                                        <input type="hidden" name="id_member" id="id_member"
                                            value="{{ $memberSelected->id_member }}">

                                        <div class="form-group">
                                            <label for="totalrp" class="control-label" style="font-weight: 600; color: #555;">Total</label>
                                            <input type="text" id="totalrp" class="form-control" readonly 
                                                style="background: #f9f9f9; border-radius: 4px; border: 1px solid #ddd;">
                                        </div>
                                        <div class="form-group">
                                            <label for="kode_member" class="control-label" style="font-weight: 600; color: #555;">Anggota</label>
                                            <div class="input-group" >
                                                <input type="text" class="form-control" id="kode_member"
                                                    value="{{ $memberSelected->kode_member }}" style="border-radius: 4px 0 0 4px; border: 1px solid #ddd;">
                                                <span class="input-group-btn" style="width: 50px; height: 50px">
                                                    <button onclick="tampilMember()" class="btn btn-info btn-flat"
                                                        type="button" style="border-radius: 0 4px 4px 0; width: 100%; height: 100%"><i class="fa fa-users"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="diskon" class="control-label" style="font-weight: 600; color: #555;">Diskon (%)</label>
                                            <input type="number" name="diskon" id="diskon" class="form-control"
                                                value="{{ !empty($memberSelected->id_member) ? $diskon : 0 }}" readonly
                                                style="background: #f9f9f9; border-radius: 4px; border: 1px solid #ddd;">
                                        </div>
                                        <div class="form-group">
                                            <label for="bayar" class="control-label" style="font-weight: 600; color: #555;">Total Bayar</label>
                                            <input type="text" id="bayarrp" class="form-control" readonly
                                                style="background: #f9f9f9; border-radius: 4px; border: 1px solid #ddd; font-weight: 600; color: #605ca8;">
                                        </div>
                                        <div class="form-group">
                                            <label for="pembayaran" class="control-label" style="font-weight: 600; color: #555;">Pembayaran</label>
                                            <select name="pembayaran" id="pembayaran" class="form-control" required
                                                style="border-radius: 4px; border: 1px solid #ddd;">
                                                <option value="">Pilih Pembayaran</option>
                                                <option value="0">Tunai</option>
                                                <option value="1">Kredit</option>
                                            </select>
                                            <span class="help-block with-errors"></span>
                                        </div>
                                        <div class="form-group cicilan-group">
                                            <label for="cicilan" class="control-label" style="font-weight: 600; color: #555;">Kredit Cicilan</label>
                                            <select name="cicilan" id="cicilan" class="form-control" required
                                                style="border-radius: 4px; border: 1px solid #ddd;">
                                                <option value="">Pilih Kredit Cicilan</option>
                                                <option value="2">x 2 Bulan</option>
                                                <option value="3">x 3 Bulan</option>
                                                <option value="4">x 4 Bulan</option>
                                                <option value="5">x 5 Bulan</option>
                                                <option value="6">x 6 Bulan</option>
                                                <option value="8">x 8 Bulan</option>
                                                <option value="9">x 9 Bulan</option>
                                                <option value="10">x 10 Bulan</option>
                                                <option value="11">x 11 Bulan</option>
                                                <option value="12">x 12 Bulan</option>
                                                <option value="24">x 24 Bulan</option>
                                            </select>
                                            <span class="help-block with-errors"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="diterima" class="control-label" style="font-weight: 600; color: #555;">Diterima</label>
                                            <input type="number" id="diterima" class="form-control" name="diterima"
                                                value="{{ $penjualan->diterima ?? 0 }}" style="border-radius: 4px; border: 1px solid #ddd;">
                                        </div>
                                        <div class="form-group">
                                            <label for="kembali" class="control-label" style="font-weight: 600; color: #555;">Kembali</label>
                                            <input type="text" id="kembali" name="kembali" class="form-control"
                                                value="0" readonly style="background: #f9f9f9; border-radius: 4px; border: 1px solid #ddd; font-weight: 600; color: #605ca8;">
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

    @includeIf('penjualan_detail.produk')
    @includeIf('penjualan_detail.member')
@endsection

@push('scripts')
    <script>
        let table, table2, table3;

        $('#pembayaran').on('change', function() {
            var pembayaranValue = $(this).val();
            if (pembayaranValue == 1) {
                $('.cicilan-group').show();
            } else {
                $('.cicilan-group').hide();
            }
        });

        // Initialize the visibility of .cicilan-group based on the initial value of #pembayaran
        if ($('#pembayaran').val() == 1) {
            $('.cicilan-group').show();
        } else {
            $('.cicilan-group').hide();
        }

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
                    url: "{{ route('search') }}",
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

            table = $('.table-penjualan').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transaksi.data', $id_penjualan) }}',
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
                            data: 'harga_jual'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'diskon'
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
                    setTimeout(() => {
                        $('#diterima').trigger('input');
                    }, 300);
                });
            table2 = $('.table-produk').DataTable();            

            $(document).on('input', '.quantity', function() {
                let self = this;
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                $.get($(this).data('url'), function(data) {
                    let stok = parseInt(data.stok);

                    if (jumlah < 1) {
                        $(self).val(1);
                        alert('Jumlah produk ' + data.nama_produk + ' tidak boleh kurang dari 1');
                        return;
                    }
                    if (jumlah > stok) {
                        $(self).val(stok);
                        alert('Jumlah produk pada produk ' + data.nama_produk +
                            ' stok yang tersedia ' + stok + '');
                        return;
                    }
                    if (jumlah > 10000) {
                        $(self).val(10000);
                        alert('Jumlah produk ' + data.nama_produk +
                            ' tidak boleh lebih dari 10000');
                        return;
                    }

                    $.post(`{{ url('/transaksi') }}/${id}`, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'put',
                            'jumlah': jumlah
                        })
                        .done(response => {
                            $(self).on('mouseout', function() {
                                table.ajax.reload(() => loadForm($('#diskon').val()));
                            });
                        })
                        .fail(errors => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                });
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('#diterima').on('input', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($('#diskon').val(), $(this).val());
            }).focus(function() {
                $(this).select();
            });

            $('.btn-simpan').on('click', function() {
                $('.form-penjualan').submit();
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
            $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
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

        function tampilMember() {
            $('#modal-member').modal('show');
            table3 = $('.table-member').DataTable();
        }

        function pilihMember(id, kode) {
            $('#id_member').val(id);
            $('#kode_member').val(kode);
            $('#diskon').val('{{ $diskon }}');
            loadForm($('#diskon').val());
            $('#diterima').val(0).focus().select();
            hideMember();
        }

        function hideMember() {
            $('#modal-member').modal('hide');
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

        function loadForm(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('Rp.' + response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
