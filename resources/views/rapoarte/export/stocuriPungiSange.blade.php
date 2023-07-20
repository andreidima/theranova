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
                        Până la data: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }}

                            <h3>
                                Total pungi: {{ $recoltariSange->count() }}
                                <br>
                                Total litri: {{ $recoltariSange->sum('cantitate') }}
                            </h3>

                    </td>
                </tr>
            </table>

            <br>

            {{-- <table style="width: 60%; margin-left:auto; margin-right:auto;">
                @foreach ($recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                    @if ($loop->first)
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
                    @endif
                    @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                        <tr>
                            <td style="border-width: 0px 0px 0px 1px">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                @if ($loop->first)
                                {{ $recoltareSange->grupa->nume ?? '' }}
                                @endif
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                {{ $recoltareSange->cod }}
                            </td>
                            <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                {{ $recoltareSange->cantitate }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3" style="text-align:left">
                                    Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($loop->last)
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @foreach ($recoltariSange->whereIn('produs.nume', ['CT', 'CTS'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                    @if ($loop->first)
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
                    @endif
                    @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                        <tr>
                            <td style="border-width: 0px 0px 0px 1px">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                @if ($loop->first)
                                {{ $recoltareSange->grupa->nume ?? '' }}
                                @endif
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                {{ $recoltareSange->cod }}
                            </td>
                            <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                {{ $recoltareSange->cantitate }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3" style="text-align:left">
                                    Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($loop->last)
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @foreach ($recoltariSange->whereIn('produs.nume', ['PC', 'PPC'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                    @if ($loop->first)
                        <tr>
                            <th colspan="4" style="text-align:center">
                                Produs: PPC
                            </th>
                        </tr>
                        <tr>
                            <th style="text-align:left">#</th>
                            <th style="text-align:center">Grupa</th>
                            <th style="text-align:center">Cod</th>
                            <th style="text-align:center">Cantitatea</th>
                        </tr>
                    @endif
                    @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                        <tr>
                            <td style="border-width: 0px 0px 0px 1px">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                @if ($loop->first)
                                {{ $recoltareSange->grupa->nume ?? '' }}
                                @endif
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                {{ $recoltareSange->cod }}
                            </td>
                            <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                {{ $recoltareSange->cantitate }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3" style="text-align:left">
                                    Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($loop->last)
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @foreach ($recoltariSange->whereIn('produs.nume', ['CRIO'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                    @if ($loop->first)
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
                    @endif
                    @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                        <tr>
                            <td style="border-width: 0px 0px 0px 1px">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                @if ($loop->first)
                                {{ $recoltareSange->grupa->nume ?? '' }}
                                @endif
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                {{ $recoltareSange->cod }}
                            </td>
                            <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                {{ $recoltareSange->cantitate }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3" style="text-align:left">
                                    Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($loop->last)
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
                @foreach ($recoltariSange->whereIn('produs.nume', ['CUT-DL'])->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                    @if ($loop->first)
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
                    @endif
                    @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                        <tr>
                            <td style="border-width: 0px 0px 0px 1px">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                @if ($loop->first)
                                {{ $recoltareSange->grupa->nume ?? '' }}
                                @endif
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                {{ $recoltareSange->cod }}
                            </td>
                            <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                {{ $recoltareSange->cantitate }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3" style="text-align:left">
                                    Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($loop->last)
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CT', 'CTS', 'PC', 'PPC', 'CRIO', 'CUT-DL'])->groupBy('produs.nume') as $recoltariSangeGrupateDupaProdus)
                    @foreach ($recoltariSangeGrupateDupaProdus->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                        @if ($loop->first)
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
                        @endif
                        @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                            <tr>
                                <td style="border-width: 0px 0px 0px 1px">
                                    {{ $loop->iteration }}
                                </td>
                                <td style="border-width: 0px 0px 0px 0px">
                                    @if ($loop->first)
                                    {{ $recoltareSange->grupa->nume ?? '' }}
                                    @endif
                                </td>
                                <td style="border-width: 0px 0px 0px 0px">
                                    {{ $recoltareSange->cod }}
                                </td>
                                <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                    {{ $recoltareSange->cantitate }}
                                </td>
                            </tr>
                            @if ($loop->last)
                                <tr>
                                    <td colspan="3" style="text-align:left">
                                        Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                    </td>
                                    <td style="text-align:right">
                                        <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if ($loop->last && !$loop->parent->last)
                            <tr>
                                <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </table> --}}




            <table style="width: 60%; margin-left:auto; margin-right:auto;">
                @foreach ($recoltariSange->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaGrupa)
                    @if ($loop->first)
                        <tr>
                            <th colspan="4" style="text-align:center">
                                Produs: {{ $recoltariSangeGrupateDupaGrupa->first()->produs->nume }}
                            </th>
                        </tr>
                        <tr>
                            <th style="text-align:left">#</th>
                            <th style="text-align:center">Grupa</th>
                            <th style="text-align:center">Cod</th>
                            <th style="text-align:center">Cantitatea</th>
                        </tr>
                    @endif
                    @foreach ($recoltariSangeGrupateDupaGrupa as $recoltareSange)
                        <tr>
                            <td style="border-width: 0px 0px 0px 1px">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                @if ($loop->first)
                                {{ $recoltareSange->grupa->nume ?? '' }}
                                @endif
                            </td>
                            <td style="border-width: 0px 0px 0px 0px">
                                {{ $recoltareSange->cod }}
                            </td>
                            <td style="text-align:right; border-width: 0px 1px 0px 0px">
                                {{ $recoltareSange->cantitate }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="3" style="text-align:left">
                                    Total <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ $recoltariSangeGrupateDupaGrupa->sum('cantitate') }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- @if ($loop->last)
                        <tr>
                            <td colspan="4" style="border:0px">&nbsp;<br>&nbsp;<br>&nbsp;</td>
                        </tr>
                    @endif --}}
                @endforeach
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
                $y = $pdf->get_height() - 20;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>


    </main>
</body>

</html>
