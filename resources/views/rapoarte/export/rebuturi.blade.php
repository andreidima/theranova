<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Rebuturi</title>
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

            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; text-align:center">
                        <h3>INSTITUTUL NAȚIONAL DE TRANSFUZIE SANGUINĂ</h3>
                        CENTRUL DE TRANSFUZIE SANGUINĂ VRANCEA
                        <br>
                        Str. CUZA VODĂ, Nr. 50-52, FOCȘANI
                        <br>
                        Telefon: 0337.401.233 / Fax: 0237.223.220
                        <hr>
                </tr>
            </table>


            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; text-align:center;">
                        <h3 style="margin: 0">RAPORT</h3>
                        Perioada: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }} - {{ \Carbon\Carbon::parse(strtok(''))->isoFormat('DD.MM.YYYY')}}
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 50%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th colspan="2" style="text-align:center">G.1. REBUT - CS</th>
                    </tr>
                    <tr>
                        <th style="text-align:center">Produs</th>
                        <th style="text-align:center">Pungi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CE</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}</td>
                    </tr>
                    {{-- @php
                        dd($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->groupBy('recoltari_sange_produs_id'))
                    @endphp --}}
                    @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProdus)
                    <tr>
                        <td>{{ $recoltariSangeGrupateDupaProdus->first()->produs->nume ?? '' }}</td>
                        <td style="text-align:right">{{ $recoltariSangeGrupateDupaProdus->count() }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="text-align:right"><b>Total<b></td>
                        <td style="text-align:right"><b>{{ $recoltariSange->count() }}</b></td>
                    </tr>
                </tbody>
            </table>

            <br><br>

            <table style="margin:0%">
                <thead>
                    <tr>
                        <th colspan="2" style="text-align:center">G.1. REBUT - CS</th>
                    </tr>
                </thead>
            </table>
            <table style="margin:0%">
                <thead>
                    <tr>
                        <th style="text-align:center">Tip CS</th>
                        @foreach ($recoltariSange->sortBy('rebut.nume')->groupBy('recoltari_sange_rebut_id') as $recoltariSangeGrupateDupaRebut)
                            <th style="text-align:center">{{ $recoltariSangeGrupateDupaRebut->first()->rebut->nume ?? '' }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CE</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}</td>
                    </tr>
                    {{-- @php
                        dd($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->groupBy('recoltari_sange_produs_id'))
                    @endphp --}}
                    @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProdus)
                    <tr>
                        <td>{{ $recoltariSangeGrupateDupaProdus->first()->produs->nume ?? '' }}</td>
                        <td style="text-align:right">{{ $recoltariSangeGrupateDupaProdus->count() }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="text-align:right"><b>Total<b></td>
                        <td style="text-align:right">{{ $recoltariSange->count() }}</td>
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
