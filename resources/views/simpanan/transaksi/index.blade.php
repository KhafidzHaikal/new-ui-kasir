@extends('layouts.master')

@section('title')
    Transaksi Simpanan
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

        .table-simpanan tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Penjualan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('simpanan.store') }}" class="form-simpanan" method="post">
                                @csrf
                                <input type="hidden" name="id_simpanan" value="{{ $id_simpanan }}">
                                <input type="hidden" name="id_member" id="id_member"
                                    value="{{ $memberSelected->id_member }}">
                                <input type="hidden" id="max_simpanan" value="{{ $simpanan_induk->nominal }}">
                                <div class="form-group row">
                                    <label for="kode_member" class="col-lg-2 control-label">Anggota</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_member"
                                                value="{{ $memberSelected->kode_member }}">
                                            <span class="input-group-btn">
                                                <button onclick="tampilMember()" class="btn btn-info btn-flat"
                                                    type="button"><i class="fa fa-arrow-right"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_anggota" class="col-lg-2 control-label">Nama Anggota</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="nama_anggota" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="gaji" class="col-lg-2 control-label">Gaji</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="gaji" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="simpanan_pokok" class="col-lg-2 control-label">Simpanan Pokok</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="simpanan_pokok" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar_pokok" class="col-lg-2 control-label">Bayar Pokok</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="bayar_pokok" class="form-control" name="bayar_pokok">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar_wajib" class="col-lg-2 control-label">Bayar Wajib</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="bayar_wajib" class="form-control" name="bayar_wajib">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar_manasuka" class="col-lg-2 control-label">Bayar Mnsuka</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="bayar_manasuka" class="form-control"
                                            name="bayar_manasuka">
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i
                                            class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @includeIf('simpanan.transaksi.member')
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

        $(function() {
            $('body').addClass('sidebar-collapse');
        });

        function tampilMember() {
            $('#modal-member').modal('show');
            table3 = $('.table-member').DataTable();
        }

        function pilihMember(id, kode, nama, gaji, simpanan_pokok) {
            $('#id_member').val(id);
            $('#kode_member').val(kode);
            $('#nama_anggota').val(nama);
            $('#gaji').val(gaji);
            $('#simpanan_pokok').val(simpanan_pokok);
            hideMember();
        }

        function hideMember() {
            $('#modal-member').modal('hide');
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }

        const bayarPokokInput = document.getElementById('bayar_pokok');
        const bayarWajibInput = document.getElementById('bayar_wajib');
        const bayarManasukaInput = document.getElementById('bayar_manasuka');
        
        const simpananPokokInput = document.getElementById('simpanan_pokok');
        const maxSimpananPokok = document.getElementById('max_simpanan');

        bayarPokokInput.addEventListener('input', function() {
            const simpananPokokValue = parseInt(simpananPokokInput.value);
            const bayarPokokValue = parseInt(bayarPokokInput.value);
            const maxSimpananPokokValue = parseInt(maxSimpananPokok.value);

            const totalValue = simpananPokokValue + bayarPokokValue;

            if (totalValue > maxSimpananPokokValue) {
                bayarPokokInput.value = maxSimpananPokokValue - simpananPokokValue;
                alert('Simpanan Pokok Tidak Boleh lebih dari Rp. 500000');
            }
        });

        const tampilBayarDiv = document.querySelector('.tampil-bayar');
        const tampilTerbilangDiv = document.querySelector('.tampil-terbilang');
        // Function to update the divs with the input values
        function updateDivs() {
            const bayarPokokValue = parseInt(bayarPokokInput.value) || 0;
            const bayarWajibValue = parseInt(bayarWajibInput.value) || 0;
            const bayarManasukaValue = parseInt(bayarManasukaInput.value) || 0;

            const totalBayar = bayarPokokValue + bayarWajibValue + bayarManasukaValue;

            const formattedTotalBayar = `Rp ${totalBayar.toLocaleString('id-ID')}`;

            tampilBayarDiv.textContent = `Total Bayar: ${formattedTotalBayar}`;
            tampilTerbilangDiv.textContent = `Terbilang: ${terbilang(formattedTotalBayar)}`;
        }

        // Function to convert number to words (terbilang)
        function terbilang(num) {
            const ones = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
            const teens = ['sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas',
                'tujuh belas', 'delapan belas', 'sembilan belas'
            ];
            const tens = ['', '', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh',
                'delapan puluh', 'sembilan puluh'
            ];

            if (num < 10) return ones[num];
            if (num < 20) return teens[num - 10];
            if (num < 100) return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? ' ' + ones[num % 10] : '');
            if (num < 1000) return ones[Math.floor(num / 100)] + ' ratus' + (num % 100 !== 0 ? ' ' + terbilang(num % 100) :
                '');
            if (num < 1000000) return terbilang(Math.floor(num / 1000)) + ' ribu' + (num % 1000 !== 0 ? ' ' + terbilang(
                num % 1000) : '');
            if (num < 1000000000) return terbilang(Math.floor(num / 1000000)) + ' juta' + (num % 1000000 !== 0 ? ' ' +
                terbilang(num % 1000000) : '');
            return 'Angka terlalu besar';
        }

        bayarPokokInput.addEventListener('input', updateDivs);
        bayarWajibInput.addEventListener('input', updateDivs);
        bayarManasukaInput.addEventListener('input', updateDivs);
    </script>
@endpush
