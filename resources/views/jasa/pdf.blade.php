<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Jasa</title>

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
            text-align: left;
            word-spacing: 0px;
            vertical-align: top;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body onload="window.print()">
    <h3 class="text-center">Laporan Pengeluaran</h3>
    <h4 class="text-center">
        Tanggal {{ tanggal_indonesia($awal, false) }}
        s/d
        Tanggal {{ tanggal_indonesia($akhir, false) }}
    </h4>

    <img src={{ asset('img/logo.png') }} alt="" style="width: 100px; height: 100px; position:absolute; left:0; top:0">
    <div style="margin-bottom: 4rem"></div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th>Keterangan</th>
                <th width="5%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jasas as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ tanggal_indonesia($row->created_at, false) }}</td>
                    <td style="text-align: left">{{ $row->deskripsi }}</td>
                    <td style="text-align: right" width="10%">{{ format_uang($row->nominal) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total Pengeluaran</strong></td>
                <td style="text-align: right"><strong>{{ format_uang($jumlah) }}</strong></td>
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
