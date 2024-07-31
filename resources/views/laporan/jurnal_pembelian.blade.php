<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan JURNAL PEMBELIAN</title>

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
            word-spacing: 0px;
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body onload="window.print()">
    <h3 class="text-center">LAPORAN JURNAL PEMBELIAN</h3>
    <h4 class="text-center">
        Tanggal {{ tanggal_indonesia($awal, false) }}
        s/d
        Tanggal {{ tanggal_indonesia($akhir, false) }}
    </h4>

    <div style="position:absolute; left:0; top:0">
        <img src={{ asset('img/logo.png') }} alt="" style="width: 70px; height: 70px">
        <p style="font-weight:700">KPRI SEJAHTERA </p>
        <p style="margin-top: -0.75rem; font-weight:700">DINKES DAN RSUD KAB CIREBON</p>
    </div>
    <div style="margin-bottom: 4rem"></div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="15%"></th>
                <th>Rincian pembelian</th>
                <th width="15%">D</th>
                <th width="15%">K</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>KAS</th>
                <td></td>
                <td></td>
                <th style="text-align: right">{{ format_uang($pembelian) }}</th>
            </tr>
            <tr>
                <th>Pembelian</th>
                <td></td>
                <th style="text-align: right">{{ format_uang($value_pembelian) }}</th>
                <td></td>
            </tr>
            @foreach ($detail_pembelian as $item)
                <tr>
                    <td></td>
                    <td style="text-align: left">{{ $item->nama_produk }}</td>
                    <td style="text-align: right">{{ format_uang($item->total_harga) }}</td>
                    <td></td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" style="text-align: center">Total Pembelian</th>
                <th style="text-align: right">{{ format_uang($value_pembelian) }}</th>
                <th style="text-align: right">{{ format_uang($pembelian) }}</th>
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
