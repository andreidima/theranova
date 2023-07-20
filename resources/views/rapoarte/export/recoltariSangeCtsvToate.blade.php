<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Recoltări Sânge CTSV</title>
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
                        <h3 style="margin: 0">RAPORT</h3>
                        Perioada: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }} - {{ \Carbon\Carbon::parse(strtok(''))->isoFormat('DD.MM.YYYY')}}
                    </td>
                </tr>
            </table>

            <br>

            <table style="width:50%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th>Donatori</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1. Donatori toți</td>
                        <td style="text-align:center">{{ $recoltariSange->unique('cod')->count() }}</td>
                    </tr>
                    <tr>
                        <td>2. Donatori noi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3. Donatori ocazionali</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4. Donatori permanenți</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table style="width:50%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th>Sânge recoltat</th>
                        <th>Pungi</th>
                        <th>Litri</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recoltariSange->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProduse)
                    <tr>
                        <td style="">{{ $recoltariSangeGrupateDupaProduse->first()->produs->nume ?? '' }}</td>
                        <td style="text-align:right">{{ $recoltariSangeGrupateDupaProduse->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeGrupateDupaProduse->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="text-align:right"><b>Total</b></td>
                        <td style="text-align:right"><b>{{ $recoltariSange->count() }}</b></td>
                        <td style="text-align:right"><b>{{ number_format($recoltariSange->sum('cantitate') / 1000, 2 ) }}</b></td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table style="width:50%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th>Livrări</th>
                        <th>Pungi</th>
                        <th>Litri</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="">în Vrancea</td>
                        <td style="text-align:right">{{ $recoltariSange->whereNotNull('comanda_id')->where('comanda.beneficiar.judet', "Vrancea")->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSange->whereNotNull('comanda_id')->where('comanda.beneficiar.judet', "Vrancea")->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="">în alte județe</td>
                        <td style="text-align:right">{{ $recoltariSange->whereNotNull('comanda_id')->where('comanda.beneficiar.judet', '<>', "Vrancea")->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSange->whereNotNull('comanda_id')->where('comanda.beneficiar.judet', '<>', "Vrancea")->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:right"><b>Total</b></td>
                        <td style="text-align:right"><b>{{ $recoltariSange->whereNotNull('comanda_id')->count() }}</b></td>
                        <td style="text-align:right"><b>{{ number_format($recoltariSange->whereNotNull('comanda_id')->sum('cantitate') / 1000, 2) }}</b></td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table style="width:50%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th>Sânge rebutat</th>
                        <th>Pungi</th>
                        <th>Litri</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recoltariSange->whereNotNull('recoltari_sange_rebut_id')->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProduse)
                    <tr>
                        <td style="">{{ $recoltariSangeGrupateDupaProduse->first()->produs->nume ?? '' }}</td>
                        <td style="text-align:right">{{ $recoltariSangeGrupateDupaProduse->count() }}</td>
                        <td style="text-align:right">{{ number_format($recoltariSangeGrupateDupaProduse->sum('cantitate') / 1000, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="text-align:right"><b>Total</b></td>
                        <td style="text-align:right"><b>{{ $recoltariSange->whereNotNull('recoltari_sange_rebut_id')->count() }}</b></td>
                        <td style="text-align:right"><b>{{ number_format($recoltariSange->whereNotNull('recoltari_sange_rebut_id')->sum('cantitate') / 1000, 2) }}</b></td>
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
