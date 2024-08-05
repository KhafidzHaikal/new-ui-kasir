<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penjualan</title>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            line-height: normal;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        h2 {
            font-size: 14px;
        }

        body {
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            font-weight: 400;
            color: #000000;
            text-align: center;
        }

        td,
        th {
            border: 1px solid #000000;
            text-align: center;
            padding: 5px;
            /* vertical-align: top; */
        }

        td {
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body onload="window.print()">
    <h3 class="text-center">Laporan Penjualan {{ $title }}</h3>
    <h4 class="text-center">
        Tanggal {{ tanggal_indonesia($awal, false) }}
        s/d
        Tanggal {{ tanggal_indonesia($akhir, false) }}
    </h4>

    <table class="table table-striped">
        <thead>
            <tr>
                @if ($title == 'TUNAI')
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="10%">Nama Kasir</th>
                    <th>Nama Barang</th>
                    <th width="5%">Jumlah</th>
                    <th width="10%">Harga Satuan</th>
                    <th>Total</th>
                @elseif ($title == 'KREDIT')
                    <th width="5%" rowspan="10">No</th>
                    <th width="5%">Tanggal</th>
                    <th width="5%">Nama Kasir</th>
                    <th width="5%">Kode Anggota</th>
                    <th width="15%">Nama Anggota</th>
                    <th width="15%">Nama Barang</th>
                    <th width="6%">Jumlah Barang</th>
                    <th width="10%">Harga Jual</th>
                    <th width="10%">Total</th>
                    <th width="10%">Kredit Cicilan</th>
                    <th width="8%">Pembayaran Per Bulan</th>
                @else
                    <th width="5%">No</th>
                    <th width="5%">Tanggal</th>
                    <th width="5%">Pembayaran</th>
                    <th width="5%">Nama Kasir</th>
                    <th width="5%">Kode Anggota</th>
                    <th width="15%">Nama Anggota</th>
                    <th width="15%">Nama Barang</th>
                    <th width="6%">Jumlah Barang</th>
                    <th width="10%">Harga Jual</th>
                    <th width="10%">Total</th>
                    <th width="10%">Kredit Cicilan</th>
                    <th width="8%">Pembayaran Per Bulan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if ($title == 'TUNAI')
                @foreach ($penjualan as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ tanggal_indonesia($row->created_at, false) }}</td>
                        <td>{{ $row->user->name }}</td>
                        <td style="text-align: left">{{ $row->produk->nama_produk }}</td>
                        <td>{{ $row->jumlah }}</td>
                        <td style="text-align: right">{{ format_uang($row->harga_jual) }}</td>
                        <td style="text-align: right">{{ format_uang($row->subtotal) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6"><strong>Total Penjualan</strong></td>
                    <td style="text-align: right"><strong>{{ format_uang($total) }}</strong></td>
                </tr>
            @elseif ($title == 'KREDIT')
                @foreach ($data_penjualan as $data)
                    @if ($data->total_item != 0)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ tanggal_indonesia($data->created_at, false) }}</td>
                            <td>{{ $data->user->name }}</td>
                            <td style="text-align: left">{{ $data->member->kode_member }}</td>
                            <td style="text-align: left">{{ $data->member->nama }}</td>
                            <td style="text-align: left" colspan="3">
                                @foreach ($penjualan as $row)
                                    @if ($data->id_penjualan == $row->id_penjualan)
                                        <table>
                                            <tr>
                                                <td width="30%" style="text-align: left; border:0">
                                                    {{ $row->produk->nama_produk }}</td>
                                                <td width="12.5%%" style="border: 0">{{ $row->jumlah }}</td>
                                                <td width="20%" style="text-align: right; border:0">
                                                    {{ format_uang($row->harga_jual) }}</td>
                                            </tr>
                                        </table>
                                    @endif
                                @endforeach
                            </td>
                            <td style="text-align: right">{{ format_uang($data->bayar) }}</td>
                            <td style="text-align: right">x {{ $data->cicilan }} Bulan</td>
                            <td style="text-align: right">{{ format_uang($data->bayar / $data->cicilan) }}</td>
                        </tr>
                    @else
                    @endif
                @endforeach
                <tr>
                    <td colspan="8"><strong>Total Penjualan</strong></td>
                    <td style="text-align: right"><strong>{{ format_uang($total) }}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
            @else
                @foreach ($data_penjualan as $data)
                    @if ($data->total_item != 0)
                        @if ($data->id_member == null)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ tanggal_indonesia($data->created_at, false) }}</td>
                                @if ($data->pembayaran == 'tunai')
                                    <td style="color: #059212; font-weight:700; text-transform:capitalize">
                                        {{ $data->pembayaran }}</td>
                                @else
                                    <td style="color: #C80036; font-weight:700; text-transform:capitalize">
                                        {{ $data->pembayaran }}</td>
                                @endif
                                <td>{{ $data->user->name }}</td>
                                <td style="text-align: left">
                                    -
                                </td>
                                <td style="text-align: left">
                                    -
                                </td>
                                <td style="text-align: left" colspan="3">
                                    @foreach ($penjualan as $row)
                                        @if ($data->id_penjualan == $row->id_penjualan)
                                            <table>
                                                <tr>
                                                    <td width="30%" style="text-align: left; border:0">
                                                        {{ $row->produk->nama_produk }}</td>
                                                    <td width="12.5%%" style="border: 0">{{ $row->jumlah }}</td>
                                                    <td width="20%" style="text-align: right; border:0">
                                                        {{ format_uang($row->harga_jual) }}</td>
                                                </tr>
                                            </table>
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align: right">{{ format_uang($data->bayar) }}</td>
                                <td style="text-align: right"> - </td>
                                <td style="text-align: right"> - </td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ tanggal_indonesia($data->created_at, false) }}</td>
                                @if ($data->pembayaran == 'tunai')
                                    <td style="color: #059212; font-weight:700; text-transform:capitalize">
                                        {{ $data->pembayaran }}</td>
                                @else
                                    <td style="color: #C80036; font-weight:700; text-transform:capitalize">
                                        {{ $data->pembayaran }}</td>
                                @endif
                                <td>{{ $data->user->name }}</td>
                                <td style="text-align: left">
                                    {{ $data->member->kode_member }}
                                </td>
                                <td style="text-align: left">
                                    {{ $data->member->nama }}
                                </td>
                                <td style="text-align: left" colspan="3">
                                    @foreach ($penjualan as $row)
                                        @if ($data->id_penjualan == $row->id_penjualan)
                                            <table>
                                                <tr>
                                                    <td width="30%" style="text-align: left; border:0">
                                                        {{ $row->produk->nama_produk }}</td>
                                                    <td width="12.5%%" style="border: 0">{{ $row->jumlah }}</td>
                                                    <td width="20%" style="text-align: right; border:0">
                                                        {{ format_uang($row->harga_jual) }}</td>
                                                </tr>
                                            </table>
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align: right">{{ format_uang($data->bayar) }}</td>
                                <td style="text-align: right">{{ $data->cicilan != 0 ? ('x '. $data->cicilan .'Bulan') : '-' }}</td>
                                <td style="text-align: right">{{ format_uang($data->bayar / $data->cicilan) }}</td>
                            </tr>
                        @endif
                    @else
                    @endif
                @endforeach
                <tr>
                    <td colspan="9"><strong>Total Penjualan</strong></td>
                    <td style="text-align: right"><strong>{{ format_uang($total) }}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div style="text-align: right">
        <p>Cirebon, {{ tanggal_indonesia($akhir, false) }}</p>
        <br>
        <br>
        <br>
        <p>{{ auth()->user()->name }}</p>
    </div>
</body>

</html>
