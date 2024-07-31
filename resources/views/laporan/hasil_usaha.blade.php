<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Hasil Usaha</title>

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
    <h3 class="text-center">LAPORAN HASIL USAHA</h3>
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
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th>Penjualan</th>
                @if (auth()->user()->level == 1 || auth()->user()->level == 4)
                    <th>Jasa</th>
                @endif
                <th>HPP</th>
                <th>Hasil Usaha</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ tanggal_indonesia($akhir, false) }}</td>
                <td>{{ format_uang($penjualan) }}</td>
                @if (auth()->user()->level == 1 || auth()->user()->level == 4)
                    <td>{{ format_uang($jasa) }}</td>
                @endif
                <td>{{ format_uang($hpp) }}</td>
                @if (auth()->user()->level == 1 || auth()->user()->level == 4)
                    <td>{{ format_uang($penjualan + $jasa - $hpp) }}</td>
                @else
                    <td>{{ format_uang($penjualan - $hpp) }}</td>
                @endif
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
