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
            padding: 0px;
            margin-top: 0px;
            border-style: solid;
            border-width: 0px;
            width: 100%;
            word-wrap:break-word;
        }

        th, td {
            padding: 1px 0px;
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
        .rotate {
            height: 100px;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        /* width: 1.5em; */
        }
        .rotate div {
            -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -10em;
                margin-right: -10em;
                /* width: 80px; */
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

            <p style="margin:0%; text-align: center"><b>G.2. REBUT REPARTIȚIE</b></p>
            <table style="margin:0%; width: 100%; font-size:9px;">
                <thead>
                    <tr>
                        {{-- <th style="vertical-align: top; text-align: right; padding-right:25px"> --}}
                            {{-- <span style="white-space: nowrap; writing-mode: vertical-lr; transform: rotate(-90deg);"> --}}
                        <th class="rotate" style="width: 100px">
                            <div>
                                Tip CS
                            </div>
                            {{-- </span> --}}
                        </th>
                        @foreach ($rebuturi as $rebut)
                            {{-- <th style="vertical-align: bottom; text-align: center; width: 10px !important;
                            "> --}}
                        {{-- <th style="text-align:center; padding:0%"> --}}
                        {{-- <th style="
                            vertical-align: center;
                            text-align: right;
                            padding-right:25px;
                            height:110px
                            "> --}}
                        {{-- <th style="font-size:8px"> --}}
                            {{-- <span style="white-space: nowrap; writing-mode: vertical-lr; transform: rotate(-90deg);"> --}}
                            {{-- <th style="text-align: center;">
                                <span style="
  -ms-writing-mode: tb-rl;
  -webkit-writing-mode: vertical-rl;
  writing-mode: vertical-rl;
  transform: rotate(-90deg);
  white-space: nowrap;
  "> --}}
                            <th class='rotate' style="height: 100px">
                                <div>
                                    {{ $rebut->nume }}
                                    </div>
                            {{-- </span> --}}
                            </th>
                        @endforeach
                        {{-- <th style="vertical-align: top; text-align: right; padding-right:25px"> --}}
                            {{-- <span style="white-space: nowrap; writing-mode: vertical-lr; transform: rotate(-90deg);"> --}}
                        <th class="rotate">
                            <div>
                                Total rebut
                            </div>
                            {{-- </span> --}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="">
                            1. Unit. eritrocitare (indiferent de tip)
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">{{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->where('recoltari_sange_rebut_id', $rebut->id)->count() }}</td>
                        @endforeach
                        <td style="text-align:center">{{ $recoltariSange->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}</td>
                    </tr>
                    <tr>
                        <td style="">
                            2. Unit. trombocitare (CT)
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">{{ $recoltariSange->whereIn('produs.nume', ['CTS'])->where('recoltari_sange_rebut_id', $rebut->id)->count() }}</td>
                        @endforeach
                        <td style="text-align:center">{{ $recoltariSange->whereIn('produs.nume', ['CTS'])->count() }}</td>
                    </tr>
                    <tr>
                        <td style="">
                            3. Unit. Plasma
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">{{ $recoltariSange->whereIn('produs.nume', ['PPC'])->where('recoltari_sange_rebut_id', $rebut->id)->count() }}</td>
                        @endforeach
                        <td style="text-align:center">{{ $recoltariSange->whereIn('produs.nume', ['PPC'])->count() }}</td>
                    </tr>
                    <tr>
                        <td style="">
                            4. Unit. PPC-DV-COVID
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">0</td>
                        @endforeach
                        <td style="text-align:center">0</td>
                    </tr>
                    <tr>
                        <td style="">
                            5. Unit. CRIO
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">0</td>
                        @endforeach
                        <td style="text-align:center">0</td>
                    </tr>
                    <tr>
                        <td style="">
                            6. Unit. afereză CUT-DL
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">{{ $recoltariSange->whereIn('produs.nume', ['CUT'])->where('recoltari_sange_rebut_id', $rebut->id)->count() }}</td>
                        @endforeach
                        <td style="text-align:center">{{ $recoltariSange->whereIn('produs.nume', ['CUT'])->count() }}</td>
                    </tr>
                    <tr>
                        <td style="">
                            7. Unit. PPC-A-DV-COVID
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">0</td>
                        @endforeach
                        <td style="text-align:center">0</td>
                    </tr>
                    @foreach ($recoltariSange->whereNotIn('produs.nume', ['CER', 'CER-SL', 'CER-DL', 'CTS', 'PPC', 'CUT'])->sortBy('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProdus)
                    <tr>
                        <td>
                            {{ $recoltariSangeGrupateDupaProdus->first()->produs->nume ?? '' }}
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align:center">{{ $recoltariSangeGrupateDupaProdus->where('recoltari_sange_rebut_id', $rebut->id)->count() }}</td>
                        @endforeach
                        <td style="text-align:center"><b>{{ $recoltariSangeGrupateDupaProdus->count() }}</b></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            <b>Total</b>
                        </td>
                        @foreach ($rebuturi as $rebut)
                            <td style="text-align: center;">{{ $recoltariSange->where('recoltari_sange_rebut_id', $rebut->id)->count() }}</td>
                        @endforeach
                        <td style="text-align: center;">
                            <b>{{ $recoltariSange->count() }}</b>
                        </td>
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
