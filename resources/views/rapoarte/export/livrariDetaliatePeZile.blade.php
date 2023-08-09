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

            <table style="margin-left: auto; margin-right: auto;">
                {{-- <thead> --}}
                    <tr>
                        <th>Data</th>
                        <th>Comanda nr.</th>
                        <th>Cod</th>
                        <th>Produs</th>
                        <th>Grupa</th>
                        <th>Cantitate</th>
                        {{-- <th>Pungi</th> --}}
                    </tr>
                {{-- </thead> --}}
                {{-- <tbody> --}}
                    @foreach($comenzi->groupBy('data') as $comenziGrupateDupaData)
                        @foreach($comenziGrupateDupaData as $comanda)
                            @foreach ($comanda->recoltariSange->sortBy('produs.nume') as $recoltareSange)
                                @if($loop->first)
                                    <tr>
                                        <td>
                                            {{ $comanda->data ? \Carbon\Carbon::parse($comanda->data)->isoFormat('DD.MM.YYYY') : '' }}
                                        </td>
                                        <td>
                                            {{ $comanda->comanda_nr }}
                                        </td>
                                        <td>{{ $recoltareSange->cod }}</td>
                                        <td>{{ $recoltareSange->produs->nume ?? '' }}</td>
                                        <td>{{ $recoltareSange->grupa->nume ?? '' }}</td>
                                        <td>{{ $recoltareSange->cantitate }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="border:0px"></td>
                                        <td style="border:0px"></td>
                                        <td>{{ $recoltareSange->cod }}</td>
                                        <td>{{ $recoltareSange->produs->nume ?? '' }}</td>
                                        <td>{{ $recoltareSange->grupa->nume ?? '' }}</td>
                                        <td>{{ $recoltareSange->cantitate }}</td>
                                    </tr>
                                @endif
                            @endforeach
                                    <tr>
                                        <td style="border:0px"></td>
                                        <td style="border:0px"></td>
                                        <td colspan="3" style="text-align:center">
                                            <b>Total: {{ $comanda->recoltariSange->count() }} pungi</b>
                                        </td>
                                        <td style="text-align:center">
                                            <b>{{ $comanda->recoltariSange->sum('cantitate') }}</b>
                                        </td>
                                    </tr>
                                            {{-- <tr>
                                                <td colspan="3" style="text-align:center">
                                                    <b>Total: {{ $comanda->recoltariSange->count() }} pungi</b>
                                                </td>
                                                <td>
                                                    <b>{{ $comanda->recoltariSange->sum('cantitate') }}</b>
                                                </td>
                                            </tr> --}}
                                @if($loop->parent->last && $loop->last)
                                @else
                                    <tr>
                                        <td colspan="6" style="border:0px">&nbsp;</td>
                                    </tr>
                                @endif
                        @endforeach
                    @endforeach
                {{-- </tbody> --}}
            </table>

            <br>

            {{-- <table style="width:50%; margin-left: auto; margin-right: auto;">
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
            </table> --}}


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
