<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Situația Sângelui și a produselor din sânge</title>
    <style>
        /* html {
            margin: 0px 0px;
        } */
        /** Define the margins of your page **/
        @page {
            margin: 0px 0px;
        }

        /* header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 0px;
        } */

        body {
            font-family: DejaVu Sans, sans-serif;
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 12px;
            margin-top: 10px;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 1cm;
        }

        * {
            /* padding: 0; */
            text-indent: 0;
        }

        table{
            border-collapse:collapse;
            margin: 0px;
            padding: 5px;
            margin-top: 0px;
            border-style: solid;
            border-width: 0px;
            width: 100%;
            word-wrap:break-word;
        }

        th, td {
            padding: 1px 10px;
            border-width: 1px;
            border-style: solid;

        }
        tr {
            border-style: solid;
            border-width: 0px;
        }
        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 0.5px;
        }
    </style>
</head>

<body>
    {{-- <header> --}}
        {{-- <img src="{{ asset('images/contract-header.jpg') }}" width="800px"> --}}
    {{-- </header> --}}

    <main>

        {{-- <div style="page-break-after: always"> --}}
        <div>

            @include('rapoarte.export.includes.header')


            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; text-align:center;">
                        <h3 style="margin: 0">Situația Sângelui și a produselor din sânge</h3>
                        Perioada: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }} - {{ \Carbon\Carbon::parse(strtok(''))->isoFormat('DD.MM.YYYY')}}
                    </td>
                </tr>
            </table>

            <br>

            <table>
                <thead>
                    <tr>
                        <th rowspan="2">
                        <th colspan="2" style="text-align: center">CE</th>
                        <th colspan="2" style="text-align: center">PPC</th>
                        <th colspan="2" style="text-align: center">CT</th>
                        <th colspan="2" style="text-align: center">CRIO</th>
                        <th colspan="2" style="text-align: center">CER-SL</th>
                        <th colspan="2" style="text-align: center">CER-DL</th>
                        <th colspan="2" style="text-align: center">CUT</th>
                    </tr>
                    <tr>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                        <td style="text-align: center">NR.P</td>
                        <td style="text-align: center">ML</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>STOC INIȚIAL</th>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['CER'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['CER'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['PPC'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['PPC'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['CTS'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['CTS'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['CRIO'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['CER-SL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['CER-SL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['CER-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['CER-DL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInitiale->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInitiale->whereIn('produs.nume', ['CUT-DL'])->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <th>RECOLTARE</th>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CER'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CER'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['PPC'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['PPC'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CTS'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CTS'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CRIO'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CER-SL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CER-SL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CER-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CER-DL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNull('intrare_id')->whereIn('produs.nume', ['CUT-DL'])->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <th>PRIMITE</th>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CER'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CER'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['PPC'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['PPC'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CTS'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CTS'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CRIO'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CER-SL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CER-SL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CER-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CER-DL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeInterval->whereNotNull('intrare_id')->whereIn('produs.nume', ['CUT-DL'])->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <th>REBUT</th>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['CER'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['CER'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['PPC'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['PPC'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['CTS'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['CTS'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['CRIO'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['CER-SL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['CER-SL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['CER-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['CER-DL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeRebutate->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeRebutate->whereIn('produs.nume', ['CUT-DL'])->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <th>LIVRARE</th>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['CER'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['CER'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['PPC'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['PPC'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['CTS'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['CTS'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['CRIO'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['CER-SL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['CER-SL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['CER-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['CER-DL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeLivrate->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeLivrate->whereIn('produs.nume', ['CUT-DL'])->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <th>STOC FINAL</th>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['CER'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['CER'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['PPC'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['PPC'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['CTS'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['CTS'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['CRIO'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['CER-SL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['CER-SL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['CER-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['CER-DL'])->sum('cantitate') / 1000, 2) }}</td>
                        <td style="text-align:right">{{ $recoltariSangeStocFinal->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeStocFinal->whereIn('produs.nume', ['CUT-DL'])->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>


        {{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
        <script type="text/php">
            if (isset($pdf)) {
                $text = "Pagina {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("helvetica");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>


    </main>
</body>

</html>
