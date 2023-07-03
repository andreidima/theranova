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

            <p style="margin:0%; text-align: center"><b>H. Număr unități validate donare standard (ST și CS validate/ eliberate din carantină*) + afereză</b></p>

            <br>

            <table>
                <tr>
                    <td style="border-width:0px; width:45%; vertical-align::top">
                        <table style="">
                            <tr>
                                <th colspan="2">
                                    H.1. Donare standard ST
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    1.a. STUA
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.b. STUA-DL
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.c. CER
                                </td>
                                <td style="text-align: right">
                                    {{ $recoltariSange->whereIn('produs.nume', ['CER'])->count() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.d. CER-SL
                                </td>
                                <td style="text-align: right">
                                    {{ $recoltariSange->whereIn('produs.nume', ['CER-SL'])->count() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.e. CER-DL
                                </td>
                                <td style="text-align: right">
                                    {{ $recoltariSange->whereIn('produs.nume', ['CER-DL'])->count() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.f. CER-DV-COVID
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.g. CT
                                </td>
                                <td style="text-align: right">
                                    {{ $recoltariSange->whereIn('produs.nume', ['CTS'])->count() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.h. PPC
                                </td>
                                <td style="text-align: right">
                                    {{ $recoltariSange->whereIn('produs.nume', ['PPC'])->count() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.i. PPC*
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.j. PPC-DV-COVID
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.k. CRIO
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.l. CRIO*
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.m. PPC-DC
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.n. PPC-DC*
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.o. ST-UP
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.p. STUP-DL
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.r. CER-UP
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1.s. CEUP-DL
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <b>Total</b>
                                </td>
                                <td style="text-align: right">
                                    <b>{{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CRIO'])->count() }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="border-width:0px; width: 10%;">
                        &nbsp;
                    </td>
                    <td style="border-width:0px; width:45%; vertical-align::top">
                        <table>
                            <tr>
                                <th colspan="2">
                                    H.2. Afereză
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    2.a. CERAF-DL
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2.b. CUT-DL
                                </td>
                                <td style="text-align: right">
                                    {{ $recoltariSange->whereIn('produs.nume', ['CUT'])->count() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2.c. CGA
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2.d. PPC-A
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2.e. PPC-A*
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2.f. PPC-A-DV-COVID
                                </td>
                                <td style="text-align: right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <b>Total</b>
                                </td>
                                <td style="text-align: right">
                                    <b>{{ $recoltariSange->whereIn('produs.nume', ['CUT'])->count() }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <br><br>

            {{-- Se afiseaza aici daca se gasesc tipuri de recoltari in afara celor standard afisate mai sus --}}
            @if ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CRIO', 'CUT'])->count() > 0)
                <table style="width: 50%; margin-left:auto; margin-right:auto;">
                    @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CRIO', 'CUT'])->groupBy('produs.nume') as $recoltariSangeGrupateDupaProdus)
                        <tr>
                            <td style="color: red">
                                {{ $recoltariSangeGrupateDupaProdus->first()->produs->nume ?? '' }}
                            </td>
                            <td style="text-align: right; color: red">
                                {{ $recoltariSangeGrupateDupaProdus->count() }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="border-width: 0px;">
                            &nbsp;
                        </td>
                    </tr>
                </table>
            @endif

            <table>
                <tr>
                    <td style="text-align: left">
                        <b>Total</b> Număr unități validate donare standard (ST și CS validate/ eliberate din carantină*) + afereză
                    </td>
                    <td style="text-align: right">
                        <b>{{ $recoltariSange->count() }}</b>
                    </td>
                </tr>
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
