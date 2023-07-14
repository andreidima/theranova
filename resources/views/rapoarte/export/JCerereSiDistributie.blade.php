<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Recoltare sânge</title>
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
                        <b>J. Cerere și distribuție</b> (unitati/litru*)
                    </td>
                </tr>



                <tr>
                    <td style="width:50%; border-bottom: 0px;">
                        J.1.1.<b>CE - Cerere</b> (indiferent de tip)
                    </td>
                    <td style="width:30%; text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="width:20%;">
                        {{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->whereNotNull('intrare_id')->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        a. ST
                    </td>
                    <td>
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px;">
                        b. CE
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->whereNotNull('intrare_id')->count() }}
                    </td>
                </tr>



                <tr>
                    <td style="border-bottom: 0px;">
                        J.1.2.<b>CE - Distribuție</b> (indiferent de tip)
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->whereNotNull('comanda_id')->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        a. ST
                    </td>
                    <td>
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px;">
                        b. CE
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->whereNotNull('comanda_id')->count() }}
                    </td>
                </tr>



                <tr>
                    <td>
                        J.2.1.<b>Trombocite - Cerere</b> (indiferent de tip)
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CTS', 'CUT'])->whereNotNull('intrare_id')->count() }}
                    </td>
                </tr>




                <tr>
                    <td style="border-bottom: 0px;">
                        J.2.2.<b>Trombocite - Distribuție</b>
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CTS', 'CUT'])->whereNotNull('comanda_id')->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        a. CT
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CTS'])->whereNotNull('comanda_id')->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px;">
                        b. CUT-DL
                    </td>
                    <td>
                        {{ $recoltariSange->whereIn('produs.nume', ['CUT'])->whereNotNull('comanda_id')->count() }}
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
