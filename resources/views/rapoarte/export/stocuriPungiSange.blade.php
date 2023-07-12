<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Stocuri pungi sânge</title>
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
                        <h3 style="margin: 0">Stocuri pungi sânge</h3>
                        Data: {{ \Carbon\Carbon::now()->isoFormat('DD.MM.YYYY')}}
                    </td>
                </tr>
            </table>

            <br>

            @foreach ($recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                @if ($loop->first)
                    <table style="width: 50%; margin-left:auto; margin-right:auto;">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align:center">
                                    Produs: CE
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:left">#</th>
                                <th style="text-align:center">Grupa</th>
                                <th style="text-align:center">Cod</th>
                                <th style="text-align:center">Cantitatea</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if ($loop->first)
                               {{ $recoltareSange->grupa->nume ?? '' }}
                            @endif
                        </td>
                        <td>
                            {{ $recoltareSange->cod }}
                        </td>
                        <td style="text-align:right">
                            {{ $recoltareSange->cantitate }}
                        </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <td colspan="3">
                                Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                            </td>
                            <td style="text-align:right">
                                <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @endforeach

            <br><br>

            @foreach ($recoltariSange->whereIn('produs.nume', ['CT', 'CTS'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                @if ($loop->first)
                    <table style="width: 50%; margin-left:auto; margin-right:auto;">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align:center">
                                    Produs: CT
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:left">#</th>
                                <th style="text-align:center">Grupa</th>
                                <th style="text-align:center">Cod</th>
                                <th style="text-align:center">Cantitatea</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if ($loop->first)
                               {{ $recoltareSange->grupa->nume ?? '' }}
                            @endif
                        </td>
                        <td>
                            {{ $recoltareSange->cod }}
                        </td>
                        <td style="text-align:right">
                            {{ $recoltareSange->cantitate }}
                        </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <td colspan="3">
                                Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                            </td>
                            <td style="text-align:right">
                                <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @endforeach

            <br><br>

            @foreach ($recoltariSange->whereIn('produs.nume', ['PC', 'PPC'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                @if ($loop->first)
                    <table style="width: 50%; margin-left:auto; margin-right:auto;">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align:center">
                                    Produs: PC
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:left">#</th>
                                <th style="text-align:center">Grupa</th>
                                <th style="text-align:center">Cod</th>
                                <th style="text-align:center">Cantitatea</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if ($loop->first)
                               {{ $recoltareSange->grupa->nume ?? '' }}
                            @endif
                        </td>
                        <td>
                            {{ $recoltareSange->cod }}
                        </td>
                        <td style="text-align:right">
                            {{ $recoltareSange->cantitate }}
                        </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <td colspan="3">
                                Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                            </td>
                            <td style="text-align:right">
                                <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @endforeach

            <br><br>

            @foreach ($recoltariSange->whereIn('produs.nume', ['CRIO'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                @if ($loop->first)
                    <table style="width: 50%; margin-left:auto; margin-right:auto;">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align:center">
                                    Produs: CRIO
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:left">#</th>
                                <th style="text-align:center">Grupa</th>
                                <th style="text-align:center">Cod</th>
                                <th style="text-align:center">Cantitatea</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if ($loop->first)
                               {{ $recoltareSange->grupa->nume ?? '' }}
                            @endif
                        </td>
                        <td>
                            {{ $recoltareSange->cod }}
                        </td>
                        <td style="text-align:right">
                            {{ $recoltareSange->cantitate }}
                        </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <td colspan="3">
                                Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                            </td>
                            <td style="text-align:right">
                                <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @endforeach

            <br><br>

            @foreach ($recoltariSange->whereIn('produs.nume', ['CUT-DL'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                @if ($loop->first)
                    <table style="width: 50%; margin-left:auto; margin-right:auto;">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align:center">
                                    Produs: CUT-DL
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:left">#</th>
                                <th style="text-align:center">Grupa</th>
                                <th style="text-align:center">Cod</th>
                                <th style="text-align:center">Cantitatea</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if ($loop->first)
                               {{ $recoltareSange->grupa->nume ?? '' }}
                            @endif
                        </td>
                        <td>
                            {{ $recoltareSange->cod }}
                        </td>
                        <td style="text-align:right">
                            {{ $recoltareSange->cantitate }}
                        </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <td colspan="3">
                                Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                            </td>
                            <td style="text-align:right">
                                <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @endforeach


            @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CT', 'CTS', 'PC', 'PPC', 'CRIO', 'CUT-DL'])->groupBy('produs.nume') as $recoltariSangeGrupateDupaProdus)
            @foreach ($recoltariSangeGrupateDupaProdus->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                @if ($loop->first)
                    <table style="width: 50%; margin-left:auto; margin-right:auto;">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align:center">
                                    Produs: {{ $recoltariSangeGrupateDupaGrupa->first()->produs->nume ?? '' }}
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:left">#</th>
                                <th style="text-align:center">Grupa</th>
                                <th style="text-align:center">Cod</th>
                                <th style="text-align:center">Cantitatea</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if ($loop->first)
                               {{ $recoltareSange->grupa->nume ?? '' }}
                            @endif
                        </td>
                        <td>
                            {{ $recoltareSange->cod }}
                        </td>
                        <td style="text-align:right">
                            {{ $recoltareSange->cantitate }}
                        </td>
                    </tr>
                    @if ($loop->last)
                        <tr>
                            <td colspan="3">
                                Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                            </td>
                            <td style="text-align:right">
                                <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @endforeach
            <br><br>
            @endforeach


            <br><br>

                    {{-- <tr>
                        <td>1.1.a CE (indiferent de tip)</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}</td>
                    </tr>
                    <tr>
                        <td>1.1.b CT</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['CT', 'CTS'])->count() }}</td>
                    </tr>
                    <tr>
                        <td>1.1.c PPC</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['PC', 'PPC'])->count() }}</td>
                    </tr>
                    <tr>
                        <td>1.1.d CRIO</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['CRIO'])->count() }}</td>
                    </tr>
                    <tr>
                        <td>1.1.e CUT-DL</td>
                        <td style="text-align:right">{{ $recoltariSange->whereIn('produs.nume', ['CUT-DL'])->count() }}</td>
                    </tr>
                    @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CT', 'CTS', 'PC', 'PPC', 'CRIO', 'CUT-DL'])->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProdus)
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
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>


    </main>
</body>

</html>
