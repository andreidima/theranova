<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Livrari Sânge</title>
    <style>
        /* html {
            margin: 0px 0px;
        } */
        /** Define the margins of your page **/
        @page {
            margin: 30px 0px;
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
            /* margin-top: 10px; */
            margin-left: 1cm;
            margin-right: 1cm;
            /* margin-bottom: 1cm; */
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
                        <br>
                        <br>
                        <h3 style="margin: 0">Raport livrări</h3>
                    </td>
                </tr>
            </table>

            <br>

            @foreach($recoltariSange->sortBy('comanda.beneficiar.id')->groupBy('comanda.beneficiar') as $recoltariSangePerBeneficiar)
                <div style="page-break-inside:avoid; margin-bottom:30px;">
                    <table style="width:70%; margin-left: auto; margin-right: auto;">
                        <thead>
                            <tr>
                                <th colspan="5">
                                    {{ $recoltariSangePerBeneficiar->first()->comanda->beneficiar->nume ?? '' }}
                                </th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Produs</th>
                                <th>Grupa</th>
                                <th>Pungi</th>
                                <th>Litri</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nrCrt = 1;
                            @endphp
                            @foreach($recoltariSangePerBeneficiar->groupBy('produs') as $recoltariSangePerProdus)
                                @foreach($recoltariSangePerProdus->groupBy('grupa') as $recoltariSangePerProdusPerGrupa)
                                    <tr>
                                        <td>
                                            {{ $nrCrt++ }}
                                        </td>
                                        {{-- @if ($loop->first) --}}
                                            <td>
                                                {{ $recoltariSangePerProdusPerGrupa->first()->produs->nume }}
                                            </td>
                                        {{-- @else
                                            <td></td>
                                        @endif --}}
                                        <td>
                                            {{ $recoltariSangePerProdusPerGrupa->first()->grupa->nume }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ $recoltariSangePerProdusPerGrupa->count() }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format((float)($recoltariSangePerProdusPerGrupa->sum('cantitate') / 1000), 2, '.', '') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                                    <tr>
                                        <th colspan="5" style="">
                                            TOTAL:
                                            @if ($numarRecoltariCE = $recoltariSangePerBeneficiar->whereIn('produs.nume', ['CER', 'CER-DL', 'CER-SL'])->count())
                                                CE={{ $numarRecoltariCE }} |
                                            @endif
                                            @if ($numarRecoltariCTS = $recoltariSangePerBeneficiar->whereIn('produs.nume', ['CTS'])->count())
                                                CTS={{ $numarRecoltariCTS }} |
                                            @endif
                                            @if ($numarRecoltariPPC = $recoltariSangePerBeneficiar->whereIn('produs.nume', ['PPC'])->count())
                                                PPC={{ $numarRecoltariPPC }} |
                                            @endif

                                            @foreach ($recoltariSangePerBeneficiar->whereNotIn('produs.nume', ['CER', 'CER-DL', 'CER-SL', 'CTS', 'PPC'])->groupBy('produs') as $recoltariSangePerBeneficiarPerProdus)
                                                {{ $recoltariSangePerBeneficiarPerProdus->first()->produs->nume ?? '' }}: {{ $recoltariSangePerBeneficiarPerProdus->count() }} |
                                            @endforeach
                                        </th>
                                    </tr>
                        </tbody>
                    </table>

                    {{-- Afisare totaluri la sfarsit --}}
                    <p style="margin:0px 150px">
                    </p>
                </div>
            @endforeach

            <br>



            </div>


        {{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
        <script type="text/php">
            if (isset($pdf)) {
                $text = "Pagina {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("helvetica");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 20;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>


    </main>
</body>

</html>
