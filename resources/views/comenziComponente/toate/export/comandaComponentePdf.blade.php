<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Comanda componente</title>
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
            /* margin-top: 10px; */
            margin-top: 1cm;
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
    {{-- <header class="text-center">
        <img src="{{ asset('images/logo.png') }}" width="400px">
    </header> --}}

    <main>

        {{-- <div style="page-break-after: always"> --}}
        <div>

            <table style="">
                <tr valign="" style="">
                    <td style="text-align: center; border-width:0px;">
                        <img src="{{ asset('images/logo2-400x103.jpg') }}" width="400px">
                        {{-- <img src="{{ public_path('images/logo.png') }}" width="250px"> --}}
                    </td>
                </tr>
                <tr>
                    <td style="border-width:0px; text-align:center;">
                        <h1 style="">
                            FISA COMANDA COMPONENTE
                        </h1>
                        <h2 style="">
                            NUME PACIENT: {{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume }}
                        </h2>
                        <h2 style="">
                            DATA: {{ \Carbon\Carbon::now()->isoFormat('DD.MM.YYYY') }}
                        </h2>
                    </td>
                </tr>
            </table>

            <br>

            <table style="width: 80%; margin-left:auto; margin-right:auto;">
                @foreach ($fisaCaz->comenziComponente as $comanda)
                    @if ($loop->first)
                        <tr>
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">PRODUCĂTOR</th>
                            <th style="text-align:center">COD PRODUS</th>
                            <th style="text-align:center">BUCĂȚI</th>
                        </tr>
                    @endif
                        <tr>
                            <td style="text-align:center">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $comanda->producator }}
                            </td>
                            <td>
                                {{ $comanda->cod_produs }}
                            </td>
                            <td style="text-align:center">
                                {{ $comanda->bucati }}
                            </td>
                            </tr>
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
