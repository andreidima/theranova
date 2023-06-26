<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Comanda</title>
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

        <div style="page-break-after: always">

            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; padding:0rem; margin:0rem; width:50%;">
                        UNITATEA: {{ $recoltareSangeComanda->unitate }}
                        <br>
                        LOCALITATEA: {{ $recoltareSangeComanda->localitate }}
                        <br>
                        JUDEȚUL: {{ $recoltareSangeComanda->judet }}
                    </td>
                    <td style="border-width:0px; padding:0rem; margin:0rem; width:50%; text-align:right;">
                        DATA COMENZII: {{ $recoltareSangeComanda->data ? \Carbon\Carbon::parse($recoltareSangeComanda->data)->isoFormat('DD.MM.YYYY') : '' }}
                    </td>
                </tr>
            </table>

            <h3 style="text-align:center">
                COMANDA NR: {{ $recoltareSangeComanda->numar }}
                <br>
                PRODUSE SANGUINE CĂTRE
                <br>
                CENTRUL DE TRANSFUZIE SANGUINĂ FOCȘANI
            </h3>


            <table>
                <tr valign="top" style="">
                    {{-- <td style="padding:2px; margin:0rem; width:50%; border:1px solid black;">
                    </td> --}}
                    <th rowspan="2">Nr. crt.</th>
                    <th rowspan="2">Tip produse</th>
                    <th colspan="8">CANTITATE (pungi) PE GRUP SANGUIN</th>
                    <th rowspan="2">Total pungi</th>
                </tr>
                <tr>
                    @foreach ($recoltareSangeGrupe as $recoltareSangeGrupa)
                        <th>{{ $recoltareSangeGrupa->nume }}</th>
                    @endforeach
                </tr>
                @foreach ($recoltareSangeComanda->recoltariSange->groupBy('recoltari_sange_produs_id') as $recoltariSangeGroupByProdus)
                    <tr>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td style="text-align: center">
                            {{ $recoltariSangeGroupByProdus->first()->produs->nume }}
                        </td>
                        @foreach ($recoltareSangeGrupe as $recoltareSangeGrupa)
                            <td style="text-align: center">
                                @if (($nrRecoltari = $recoltariSangeGroupByProdus->where('recoltari_sange_grupa_id', $recoltareSangeGrupa->id)->count()) !== 0)
                                    {{ $nrRecoltari }}
                                @endif
                            </td>
                        @endforeach
                        <td style="text-align: center">
                            {{ $recoltariSangeGroupByProdus->count() }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; padding:0rem; margin:0rem; width:50%; text-align:center;">
                        MEDIC PPSCRIPTOR,
                    </td>
                    <td style="border-width:0px; padding:0rem; margin:0rem; width:50%; text-align:center;">
                        AS GARDĂ,
                    </td>
                </tr>
            </table>

            <br><br>

            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; padding:0rem; margin:0rem; width:50%;">
                        CTS FOCȘANI:
                        <br>
                        BON DE LIVRARE NR: {{ $recoltareSangeComanda->numar }}
                        <br>
                        AVIZ NR:
                    </td>
                    <td style="border-width:0px; padding:0rem; margin:0rem; width:50%; text-align:right;">
                        CĂTRE SPITALUL: {{ $recoltareSangeComanda->unitate }}
                    </td>
                </tr>
            </table>

            <table>
                <tr valign="top" style="">
                    <th>Fel produs</th>
                    <th>Grupa</th>
                    <th>Rh</th>
                    <th>Cod</th>
                    <th>Cant.</th>
                    <th style="border-width:0px;"></th>
                    <th>Fel produs</th>
                    <th>Grupa</th>
                    <th>Rh</th>
                    <th>Cod</th>
                    <th>Cant.</th>
                </tr>
                @foreach ($recoltareSangeComanda->recoltariSange->sortBy('recoltari_sange_produs_id')->sortBy('recoltari_sange_grupa_id') as $recoltareSange)
                    @if ($loop->odd) <tr> @endif
                        <td style="text-align:center;">{{ $recoltareSange->produs->nume ?? '' }}</td>
                        <td style="text-align:center;">{{ substr_replace(($recoltareSange->grupa->nume ?? ''), "", -1) }}</td>
                        <td style="text-align:center;">{{ substr(($recoltareSange->grupa->nume ?? ''), -1) }}</td>
                        <td>{{ $recoltareSange->cod }}</td>
                        <td style="text-align:right;">{{ $recoltareSange->cantitate }}</td>
                    @if ($loop->odd) <td style="border-width:0px;"></td> @endif
                    @if ($loop->even) </tr> @endif
                @endforeach
            </table>

            <br>

            <table style="">
                <tr valign="top" style="">
                    <td style="border-width:0px;">
                        TOTAL: {{ $recoltareSangeComanda->recoltariSange->count() }}
                        <br>
                        <br>
                    </td>
                    <td style="border-width:0px;">
                        ST:
                    </td>
                    <td style="border-width:0px;">
                        CE:
                    </td>
                    <td style="border-width:0px;">
                        CT:
                    </td>
                    <td style="border-width:0px;">
                        PPC:
                    </td>
                    <td style="border-width:0px;">
                        PPD:
                    </td>
                    <td style="border-width:0px;">
                        CRIO:
                    </td>
                </tr>
                <tr valign="top" style="">
                    <td colspan="2" style="border-width:0px;">
                        EXPEDITOR:
                    </td>
                    <td colspan="2" style="border-width:0px;">
                        PRIMITOR:
                    </td>
                    <td colspan="2" style="border-width:0px; text-align:center;">
                        DATA:
                        <br>
                        {{ $recoltareSangeComanda->data ? \Carbon\Carbon::parse($recoltareSangeComanda->data)->isoFormat('DD.MM.YYYY') : '' }}
                    </td>
                    <td style="border-width:0px;">
                        ORA:
                    </td>
                </tr>
            </table>



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
