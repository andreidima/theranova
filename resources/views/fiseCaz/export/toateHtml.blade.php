@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Fise Caz</title>
    <style>
        /* html {
            margin: 0px 0px;
        } */
        /** Define the margins of your page **/
        @page {
            margin: 0px 0px;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 0px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 12px;
            /* margin-top: 1cm; */
            margin-top: 0.3cm;
            margin-left: 0.3cm;
            margin-right: 0.3cm;
            margin-bottom: 0.3cm;
        }

        * {
            /* padding: 0; */
            text-indent: 0;
            text-align: justify;
        }

        table{
            border-collapse:collapse;
            margin: 0px;
            padding: 5px;
            margin-top: 0px;
            border-style: solid;
            border-width: 1px;
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
    {{-- <header style="margin:0px 0px 0px 0px; text-align: center;">
        <img src="{{ asset('images/logo2-400x103.jpg') }}" width="400px">
    </header> --}}

    <main>
        <table>
            <tr>
                <th>ANUL</th>
                <th>LUNA</th>
                <th>NUME PACIENT</th>
                <th>LOCALITATE DE DOMICILIU</th>
                <th>JUDET</th>
                <th>TELEFON<br>(pacient/<br>apartinator)</th>
                <th>TIP PROTEZA</th>
                <th>valoare<br>-lei</th>
                <th>DECIZIE<br>sau<br>CASH<br>sau<br>VOUCHER</th>
                <th>PERSOANA<br>VANZARI</th>
                <th>PERSOANA<br>TEHNIC</th>
                <th>SURSA:<br>De unde a aflat de Theranova</th>
            </tr>
            @foreach ($fiseCaz as $fisaCaz)
                <tr>
                    <td>
                        {{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('YYYY') : '' }}
                    </td>
                    <td>
                        {{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('MM') : '' }}
                    </td>
                    <td>
                        {{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume ?? ''}}
                    </td>
                    <td>
                        {{ $fisaCaz->pacient->localitate ?? '' }}
                    </td>
                    <td>
                        {{ $fisaCaz->pacient->judet ?? '' }}
                    </td>
                    <td>
                        {{ $fisaCaz->pacient->telefon ?? '' }}
                        <br>
                            @foreach (($fisaCaz->pacient->apartinatori ?? []) as $apartinator)
                                @if ($apartinator->telefon)
                                    {{-- {{ $apartinator->nume }} {{ $apartinator->prenume }}: {{ $apartinator->telefon }} --}}
                                    {{ $apartinator->telefon }}
                                    <br>
                                @endif
                            @endforeach
                    </td>
                    <td>
                        {{ $fisaCaz->dateMedicale->first()->tip_proteza ?? ''}}
                    </td>
                    <td>
                        {{ $fisaCaz->ofertaAcceptata->pret ?? '' }}
                    </td>
                    <td>
                        {{ $fisaCaz->cerinte->first()->sursa_buget ?? ''}}
                    </td>
                    <td>
                        {{ ($fisaCaz->userVanzari->name ?? '') }}
                    </td>
                    <td>
                        {{ ($fisaCaz->userTehnic->name ?? '') }}
                    </td>
                    <td>
                        {{ $fisaCaz->pacient->cum_a_aflat_de_theranova ?? '' }}
                    </td>
                </tr>
            @endforeach
        </table>

    </main>
</body>

</html>
