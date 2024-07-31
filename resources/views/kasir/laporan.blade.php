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
            font-family: 'Roboto', sans-serif;
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
            letter-spacing: 2rem;
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

<body>
    <h3 class="text-center">Laporan Penjualan</h3>
    <h4 class="text-center">
        Tanggal {{ tanggal_indonesia($awal, false) }}
        s/d
        Tanggal {{ tanggal_indonesia($akhir, false) }}
    </h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="10%">Pembayaran</th>
                <th width="10%">Nama Kasir</th>
                <th width="10%">Tunai</th>
                <th width="10%">Kredit</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $row)
                @if ($row->total_item != 0)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ tanggal_indonesia($row->created_at, false) }}</td>
                        @if ($row->pembayaran == 'tunai')
                            <td style="color: #059212; font-weight:700; text-transform:capitalize">
                                Tunai</td>
                        @else
                            <td style="color: #C80036; font-weight:700; text-transform:capitalize">
                                Kredit</td>
                        @endif
                        <td>{{ $row->user->name }}</td>
                        @if ($row->pembayaran == 'tunai')
                            <td style="text-align: right">
                                {{ format_uang($row->bayar) }}</td>
                        @else
                            <td style="text-align: right">
                                0</td>
                        @endif
                        @if ($row->pembayaran == 'kredit')
                            <td style="text-align: right">
                                {{ format_uang($row->bayar) }}</td>
                        @else
                            <td style="text-align: right">
                                0</td>
                        @endif
                        <td style="text-align: right">{{ format_uang($row->bayar) }}</td>
                    </tr>
                @else
                @endif
            @endforeach
            <tr>
                <td colspan="4"><strong>Total Penjualan</strong></td>
                <td style="text-align: right"><strong>{{ format_uang($total_tunai) }}</strong></td>
                <td style="text-align: right"><strong>{{ format_uang($total_kredit) }}</strong></td>
                <td style="text-align: right"><strong>{{ format_uang($total) }}</strong></td>
            </tr>
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
