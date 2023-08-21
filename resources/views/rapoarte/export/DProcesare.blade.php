<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>D. Procesare</title>
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
            padding: 0px;
            margin-top: 0px;
            border-style: solid;
            border-width: 0px;
            width: 100%;
            word-wrap:break-word;
        }

        th, td {
            padding: 1px 3px;
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
                        <h3 style="margin: 0">RAPORT</h3>
                        Perioada: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }} - {{ \Carbon\Carbon::parse(strtok(''))->isoFormat('DD.MM.YYYY')}}
                    </td>
                </tr>
            </table>

            <br>


            <br>

            <table>
                <tr>
                    <td colspan="3">
                        <b>II. D. Procesare
                    </td>
                </tr>
                <tr>
                    <td colspan="2">II.D.1. Număr unități ST (450 ml) INTRATE în procesare TOTAL</td>
                    <td style="width: 50px; text-align:center;">{{ $recoltariSangeFaraRebutRecoltare->unique('cod')->count() }}</td>
                </tr>
                <tr>
                    <td colspan="2">II.D.2. Număr unități ST și CS REZULTATE din procesare TOTAL</td>
                    <td style="width: 50px; text-align:center;">{{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CRIO'])->count() }}</td>
                </tr>
                <tr>
                    <td rowspan="15" style="text-align: right; border-right: 0px;">
                        din care,
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px">
                        2.1. ST-UA
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.2. STUA-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.3. CER
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.4. CER-SL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER-SL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.5. CER-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.6. CER-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.7. CT
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CTS'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.8. PPC
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.9. PPC-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.10. CRIO
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CRIO'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.11. PPC-DC
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.12. ST-UP
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.13. STUP-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        2.14. CER-UP
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 1px 0px;">
                        2.15. CEUP-DL
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>



                <tr>
                    <td colspan="2">II.D.3. Număr unități ST și CS CONFORME rezultate din procesare TOTAL</td>
                    <td style="width: 50px; text-align:center;">{{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CRIO'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CRIO'])->count() }}</td>
                </tr>
                <tr>
                    <td rowspan="15" style="text-align: right; border-right: 0px;">
                        din care,
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px">
                        3.1. ST-UA
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.2. STUA-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.3. CER
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER'])->count()}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.4. CER-SL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER-SL'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER-SL'])->count()}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.5. CER-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CER-DL'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER-DL'])->count()}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.6. CER-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.7. CT
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CTS'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CTS'])->count()}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.8. PPC
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['PPC'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['PPC'])->count()}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.9. PPC-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.10. CRIO
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CRIO'])->count() - $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CRIO'])->count()}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.11. PPC-DC
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.12. ST-UP
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.13. STUP-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        3.14. CER-UP
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 1px 0px;">
                        3.15. CEUP-DL
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
            </table>

            <div style="page-break-after: always"></div>



            <br><br>

            <table>
                <tr>
                    <td colspan="2">II.D.4. Număr unități ST și CS NECONFORME rezultate din procesare (rebut procesare, aspect, masa, volum, integritate etc) TOTAL</td>
                    <td style="width: 50px; text-align:center;">
                        {{ $recoltariSangeRebutProcesareAspectChilos->count() }}
                    </td>
                </tr>
                <tr>
                    <td rowspan="15" style="text-align: right; border-right: 0px;">
                        din care,
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px">
                        4.1. ST-UA
                    </td>
                    <td style="text-align:center; border-bottom:dashed;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.2. STUA-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.3. CER
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.4. CER-SL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER-SL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.5. CER-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.6. CER-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.7. CT
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CTS'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.8. PPC
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.9. PPC-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.10. CRIO
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeRebutProcesareAspectChilos->whereIn('produs.nume', ['CRIO'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.11. PPC-DC
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.12. ST-UP
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.13. STUP-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        4.14. CER-UP
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 1px 0px;">
                        4.15. CEUP-DL
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td colspan="2">II.D.5. Număr CT rezultate din procedurile de afereză finalizate TOTAL</td>
                    <td style="text-align:center;">0</td>
                </tr>
                <tr>
                    <td rowspan="5" style="text-align: right; border-right: 0px;">
                        din care,
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px">
                        5.1. PPC-A
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        5.2. PPC-A-DV-COVID
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        5.3. CUD-DL*
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CUT'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        5.4. CERAF-DL
                    </td>
                    <td style="text-align:center; border-bottom:dashed">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-width: 0px 1px 0px 0px;">
                        5.5. CGA
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-width: 1px 0px;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">*5.3.1. număr unități CT echivalent CUT-DL</td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeFaraRebutRecoltare->whereIn('produs.nume', ['CUT'])->count() * 5 }}
                    </td>
                </tr>
            </table>

            <br><br>


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
