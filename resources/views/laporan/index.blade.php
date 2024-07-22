@extends('layouts.master')

@section('title')
    Laporan Pendapatan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@push('css')
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Laporan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-flat"><i class="fa fa-plus-circle"></i>
                        Ubah Periode</button>
                    <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank"
                        class="btn btn-success btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>
                    <button type="button" class="btn btn-primary btn-flat" data-toggle="modal"
                        data-target=".bd-example-modal-lg"><i class="fa fa-file-excel-o"></i> Laba-Rugi</button>

                    {{-- Modal Laba Rugi --}}
                    {{-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Laba-Rugi</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="awal" id="awal"
                                                    class="form-control datepicker" required autofocus
                                                    value="{{ request('awal') }}" style="border-radius: 0 !important;">
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="akhir" id="akhir"
                                                    class="form-control datepicker" required
                                                    value="{{ request('akhir') ?? date('Y-m-d') }}"
                                                    style="border-radius: 0 !important;">
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/laporan/laba-rugi/'+document.getElementById('awal').value+ '/' +document.getElementById('akhir').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- End Modal Laba Rugi --}}

                    <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#myModal"><i
                            class="fa fa-file-excel-o"></i> HPP</button>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan HPP</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
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
                                                <input type="date" class="form-control" id="tanggal_akhir" required
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/laporan/hpp/'+document.getElementById('tanggal_awal').value+ '/' +document.getElementById('tanggal_akhir').value"
                                        class="btn btn-primary">Cetak</a>

                                    {{-- <a target="_blank"
                                        onclick="openLaporanHPP(document.getElementById('tanggal_awal').value, document.getElementById('tanggal_akhir').value)"
                                        class="btn btn-primary">Cetak</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#myModal1"><i
                            class="fa fa-file-excel-o"></i> Hasil Usaha</button>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModal1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Hasil Usaha</h5>
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
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/laporan/hasil_usaha/'+document.getElementById('awal').value+ '/' +document.getElementById('akhir').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#shu"><i
                            class="fa fa-file-excel-o"></i> SHU</button>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="shu">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan SHU</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="awal_tanggal" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="akhir_tanggal" required
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/laporan/shu/'+document.getElementById('awal_tanggal').value+ '/' +document.getElementById('akhir_tanggal').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-warning btn-flat" data-toggle="modal"
                        data-target="#jurnal_penjualan"><i class="fa fa-file-excel-o"></i> Jurnal Penjualan</button>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="jurnal_penjualan">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Jurnal Penjualan</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="tanggal_aw" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="tanggal_ak" required
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/laporan/jurnal_penjualan/'+document.getElementById('tanggal_aw').value+ '/' +document.getElementById('tanggal_ak').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-warning btn-flat" data-toggle="modal"
                        data-target="#jurnal_pembelian"><i class="fa fa-file-excel-o"></i> Jurnal Pembelian</button>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="jurnal_pembelian">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Laporan Jurnal Pembelian</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="tang_awal" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" id="tang_akhir" required
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a target="_blank"
                                        onclick="this.href='/laporan/jurnal_pembelian/'+document.getElementById('tang_awal').value+ '/' +document.getElementById('tang_akhir').value"
                                        class="btn btn-primary">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Penjualan</th>
                            <th>Pembelian</th>
                            <th>Pengeluaran</th>
                            <th>Pendapatan</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('laporan.form')
@endsection

@push('scripts')
    <script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}',
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
                        data: 'penjualan'
                    },
                    {
                        data: 'pembelian'
                    },
                    {
                        data: 'pengeluaran'
                    },
                    {
                        data: 'pendapatan'
                    }
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false,
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        function updatePeriode() {
            $('#modal-form').modal('show');
        }
    </script>
@endpush
