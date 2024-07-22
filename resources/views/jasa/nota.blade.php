<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Jasa</title>

    <?php
    $style = '
            <style>
                * {
                    font-family: "consolas", sans-serif;
                }
                p {
                    display: block;
                    margin: 3px;
                    font-size: 12pt;
                }
                table td {
                    font-size: 11pt;
                }
                .text-center {
                    text-align: center;
                }
                .text-right {
                    text-align: right;
                }
        
                @media print {
                    @page {
                        margin: 0;
                        size: 75mm 
            ';
    ?>
    <?php
    $style .= !empty($_COOKIE['innerHeight']) ? $_COOKIE['innerHeight'] . 'mm; }' : '}';
    ?>
    <?php
    $style .= '
                    html, body {
                        width: 70mm;
                    }
                    .btn-print {
                        display: none;
                    }
                }
            </style>
            ';
    ?>

    {!! $style !!}
</head>

<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div style="display:flex; justify-content:center">
        <img src={{ asset('img/logo-koperasi.png') }} alt="" style="width: 70px; height: 70px">
    </div>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h3>
        <p>{{ strtoupper($setting->alamat) }}</p>
    </div>
    <br>
    <div>
        <p style="float: left; width:50%">{{ $waktu }}</p>
        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No: {{ tambah_nol_didepan($detail->id_jasa, 10) }}</p>
    <p class="text-center">===================================</p>

    <br>
    <table width="100%" style="border: 0;">
        <tr>
            <td>{{ $detail->deskripsi }}</td>
            <td></td>
            <td class="text-right">{{ format_uang($detail->nominal) }}</td>
        </tr>
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Jasa:</td>
            <td class="text-right">{{ format_uang($detail->nominal) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight=" + ((height + 50) * 0.264583);
    </script>
</body>

</html>
