<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Intrări Sânge CTSV</title>
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
            padding: 1px 5px;
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
                        <p style="margin:0%; text-align: center"><b>Raport INTRĂRI detaliat pe zile</b></p>
                        Perioada: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }} - {{ \Carbon\Carbon::parse(strtok(''))->isoFormat('DD.MM.YYYY')}}
                    </td>
                </tr>
            </table>

            <table style="width:50%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th rowspan="2">Data</th>
                        <th rowspan="2">Nr. pungi</th>
                        @foreach ($recoltariSange->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProduse)
                            <th colspan="2">{{ $recoltariSangeGrupateDupaProduse->first()->produs->nume ?? '' }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($recoltariSange->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProduse)
                            <th>Pungi</th>
                            <th>Litri</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($recoltariSange->sortBy('comanda.data')->groupBy('comanda.data') as $recoltariSangeGrupateDupaData)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($recoltariSangeGrupateDupaData->first()->intrare->data ?? '')->isoFormat('DD.MM.YYYY') }}
                            </td>
                            <td style="text-align:center">
                                {{ $recoltariSangeGrupateDupaData->count() }}
                            </td>
                            @foreach ($recoltariSange->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProduse)
                                <td style="text-align:right">{{ $recoltariSangeGrupateDupaProduse->where('comanda.data', $recoltariSangeGrupateDupaData->first()->comanda->data)->count() }}</td>
                                <td style="text-align:right">{{ number_format($recoltariSangeGrupateDupaProduse->where('comanda.data', $recoltariSangeGrupateDupaData->first()->comanda->data)->sum('cantitate') / 1000, 2) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                        <tr>
                            <td style="text-align: center">
                                <b>Total</b>
                            </td>
                            <td style="text-align:center">
                                <b>{{ $recoltariSange->count() }}</b>
                            </td>
                            @foreach ($recoltariSange->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProduse)
                                <td style="text-align:right"><b>{{ $recoltariSangeGrupateDupaProduse->count() }}</b></td>
                                <td style="text-align:right"><b>{{ number_format($recoltariSangeGrupateDupaProduse->sum('cantitate') / 1000, 2) }}</b></td>
                            @endforeach
                        </tr>
                </tbody>
            </table>

            <br>

                                @foreach ($recoltariSange as $recoltareSange)
                                    {{ $recoltareSange->data }} - {{ $recoltareSange->cod }}
                                    <br>
                                @endforeach


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
