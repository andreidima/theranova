<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Recoltare sânge</title>
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
            padding: 1px 3px;
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


            <br>

            <table>
                <tr>
                    <td colspan="3">
                        <b>J. Cerere și distribuție</b> (unități/litru*)
                    </td>
                </tr>



                <tr>
                    <td style="width:50%; border-bottom: 0px;">
                        J.1.1.<b>CE - Cerere</b> (indiferent de tip)
                    </td>
                    <td style="width:35%; text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="width:15%; text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        a. ST
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px;">
                        b. CE
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>



                <tr>
                    <td style="border-bottom: 0px;">
                        J.1.2.<b>CE - Distribuție</b> (indiferent de tip)
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        a. ST
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px;">
                        b. CE
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>



                <tr>
                    <td>
                        J.2.1.<b>Trombocite - Cerere</b> (indiferent de tip)
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CTS', 'CUT'])->count() }}
                    </td>
                </tr>



                <tr>
                    <td style="border-bottom: 0px;">
                        J.2.2.<b>Trombocite - Distribuție</b>
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CTS', 'CUT'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        a. CT
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CTS'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px;">
                        b. CUT-DL
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CUT'])->count() }}
                    </td>
                </tr>



                <tr>
                    <td>
                        J.3.1.<b>PPC - Cerere</b> (indiferent de tip)
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right">
                        3.1.1. din care, PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>



                <tr>
                    <td style="border-bottom: 0px;">
                        J.3.2.<b>PPC - Distribuție</b>
                    </td>
                    <td style="text-align: right">
                        <b>3.2.1. Total unități</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        3.2.1.a. PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        3.2.1.b. PPC-A
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        3.2.1.c. PPC-A-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right">
                        <b>3.2.2. Total litri</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['PPC'])->sum('cantitate') }}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        3.2.2.a. PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        3.2.2.b. PPC-A
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:dashed">
                        3.2.2.c. PPC-A-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>



                <tr>
                    <td>
                        J.4.1.<b>CRIO - Cerere</b>
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CRIOP'])->count() }}
                    </td>
                </tr>



                <tr>
                    <td style="border-bottom: 0px;">
                        J.4.2.<b>CRIO - Distribuție</b>
                    </td>
                    <td style="text-align: right">
                        4.2.1.<b>Total unități</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CRIOP'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px;">

                    </td>
                    <td style="text-align: right">
                        4.2.2.<b>Total litri</b>
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuite->whereIn('produs.nume', ['CRIOP'])->sum('cantitate') }}
                    </td>
                </tr>



                <tr>
                    <td>
                        J.5.1.<b>PPC decropp - Cerere</b>
                    </td>
                    <td style="text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>



                <tr>
                    <td style="border-bottom: 0px;">
                        J.5.2.<b>PPC-DC - Distribuție</b>
                    </td>
                    <td style="text-align: right">
                        5.2.1.<b>Total unități</b>
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 0px;">

                    </td>
                    <td style="text-align: right">
                        5.2.2.<b>Total litri</b>
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
            </table>

            <p style="page-break-after: always"></p>

            <br><br><br><br><br><br>

            <table>
                <tr>
                    <td style="width:50%; border-bottom: 0px;">
                        J.6. Număr unități CS <b>primite de la alte CTS</b>
                    </td>
                    <td style="width:35%; text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="width:15%; text-align:center;">
                        {{ $recoltariSangePrimite->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        a. ST (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        b. CE (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangePrimite->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        c. CT
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangePrimite->whereIn('produs.nume', ['CT', 'CTS'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        d. CUT-DL
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangePrimite->whereIn('produs.nume', ['CUT', 'CUD-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        e. PPC
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangePrimite->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        f. PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        g. PPC-A
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        h. PPC-AF-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        i. CRIO
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangePrimite->whereIn('produs.nume', ['CRIO', 'CRIOP'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        j. PPC-DC
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; border-bottom: 0px;">
                        J.7. Număr unități CS <b>distribuite către spitalele din județ</b>
                    </td>
                    <td style="width:35%; text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="width:15%; text-align:center;">
                        {{ $recoltariSangeDistribuiteInJudet->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        a. ST (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        b. CE (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteInJudet->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        c. CT
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteInJudet->whereIn('produs.nume', ['CT', 'CTS'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        d. CUT-DL
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteInJudet->whereIn('produs.nume', ['CUT', 'CUD-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        e. PPC
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteInJudet->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        f. PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        g. PPC-A
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        h. PPC-AF-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        i. CRIO
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteInJudet->whereIn('produs.nume', ['CRIO', 'CRIOP'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-top:0px;"">
                        j. PPC-DC
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
            </table>

            <p style="page-break-after: always"></p>

            <br><br><br><br><br><br>

            <table>
                <tr>
                    <td style="width:50%; border-bottom: 0px;">
                        J.8. Număr unități CS <b>distribuite către spitalele din afara județului</b>
                    </td>
                    <td style="width:35%; text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="width:15%; text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        a. ST (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        b. CE (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        c. CT
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        d. CUT-DL
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        e. PPC
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        f. PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        g. PPC-A
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        h. PPC-AF-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        i. CRIO
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        j. PPC-DC
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; border-bottom: 0px;">
                        J.9. Număr unități CS <b>distribuite către alte CTS*</b>
                    </td>
                    <td style="width:35%; text-align: right">
                        <b>Total</b>
                    </td>
                    <td style="width:15%; text-align:center;">
                        {{ $recoltariSangeDistribuiteCatreAlteCts->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">
                        din care
                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        a. ST (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        b. CE (indiferent de tip)
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteCatreAlteCts->whereIn('produs.nume', ['CER', 'CER-SL', 'CER-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        c. CT
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteCatreAlteCts->whereIn('produs.nume', ['CT', 'CTS'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        d. CUT-DL
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteCatreAlteCts->whereIn('produs.nume', ['CUT', 'CUD-DL'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        e. PPC
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteCatreAlteCts->whereIn('produs.nume', ['PPC'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        f. PPC-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        g. PPC-A
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        h. PPC-AF-DV-COVID
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px; border-bottom: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-bottom:0px; border-top:0px;">
                        i. CRIO
                    </td>
                    <td style="text-align:center;">
                        {{ $recoltariSangeDistribuiteCatreAlteCts->whereIn('produs.nume', ['CRIO', 'CRIOP'])->count() }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; border-top: 0px; border-right: 0px;">

                    </td>
                    <td style="text-align: right; border-left: 0px; border-top:0px;"">
                        j. PPC-DC
                    </td>
                    <td style="text-align:center;">
                        0
                    </td>
                </tr>
            </table>

            <br><br>


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
