<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }

        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }

        table.data {
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <img src="{{ asset('img/logo.png') }}" width="30">
                <br>
                {{ $setting->alamat }}
                <br>
                <br>
            </td>
            <td>Tanggal</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Kode Anggota</td>
            <td>: {{ $penjualan->member->kode_member ?? '' }}</td>
        </tr>
        <tr>
            <td>Nama Anggota</td>
            <td>: {{ $penjualan->member->nama ?? '' }}</td>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td>{{ $item->produk->kode_produk }}</td>
                    <td class="text-right">{{ format_uang($item->harga_jual) }}</td>
                    <td class="text-right">{{ format_uang($item->jumlah) }}</td>
                    <td class="text-right">{{ $item->diskon }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><b>Total Harga</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->total_harga) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Diskon</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->diskon) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Total Bayar</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->bayar) }}</b></td>
            </tr>
            @if ($penjualan->pembayaran == 'kredit')
                <tr>
                    <td colspan="6" class="text-right"><b>Kredit Cicilan</b></td>
                    <td class="text-right"><b>x {{ $penjualan->cicilan }} Bulan</b></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right"><b>Pembayaran Per Bulan</b></td>
                    <td class="text-right"><b>{{ format_uang($penjualan->bayar / $penjualan->cicilan) }}</b></td>
                </tr>
            @else
                <tr>
                    <td colspan="6" class="text-right"><b>Diterima</b></td>
                    <td class="text-right"><b>{{ format_uang($penjualan->diterima) }}</b></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right"><b>Kembali</b></td>
                    <td class="text-right"><b>{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</b></td>
                </tr>
            @endif
        </tfoot>
    </table>

    <table width="100%">
        <tr>
            <td>
                Anggota
                <br>
                <br>
                <br>
                <br>
                <br>
                {{ $penjualan->member->nama ?? '' }}
            </td>
            <td class="text-center">
                Kasir
                <br>
                <br>
                <br>
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
</body>

</html>
