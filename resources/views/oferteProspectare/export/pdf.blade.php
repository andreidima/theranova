<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        h2 { font-size: 15px; margin-top: 22px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background: #e9ecef; }
        .no-border td { border: 0; padding: 3px 0; }
        .right { text-align: right; }
        .muted { color: #666; }
        .total { font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Oferta Theranova #{{ $oferta->id }}</h1>
    <p class="muted">
        Data oferta: {{ optional($oferta->data_ofertei)->format('d.m.Y') }}
        | Valabila pana la: {{ optional($oferta->valabila_pana_la)->format('d.m.Y') }}
    </p>

    <h2>Client</h2>
    <table class="no-border">
        <tr>
            <td><b>Nume:</b> {{ $oferta->nume_client }}</td>
            <td><b>Telefon:</b> {{ $oferta->telefon }}</td>
        </tr>
        <tr>
            <td><b>Email:</b> {{ $oferta->email }}</td>
            <td><b>Localitate / judet:</b> {{ $oferta->localitate }} / {{ $oferta->judet }}</td>
        </tr>
    </table>

    <h2>Date solicitare</h2>
    <table class="no-border">
        <tr>
            <td><b>Tip lucrare:</b> {{ $oferta->tip_lucrare_solicitata }}</td>
            <td><b>Nivel activitate:</b> {{ $oferta->nivel_de_activitate }}</td>
        </tr>
        <tr>
            <td><b>Greutate:</b> {{ $oferta->greutate }}</td>
            <td><b>A mai purtat:</b> {{ is_null($oferta->a_mai_purtat_proteza) ? '' : ($oferta->a_mai_purtat_proteza ? 'DA' : 'NU') }}</td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>Parte amputata</th>
                <th>Amputatie</th>
            </tr>
        </thead>
        <tbody>
            @forelse($oferta->amputatii as $amputatie)
                <tr>
                    <td>{{ $amputatie->parte_amputata }}</td>
                    <td>{{ $amputatie->amputatie }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ $oferta->parte_amputata }}</td>
                    <td>{{ $oferta->amputatie }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Variante oferta</h2>
    @if($oferta->variante->isNotEmpty())
        @foreach($oferta->variante as $varianta)
            <table>
                <thead>
                    <tr>
                        <th colspan="4">
                            {{ $varianta->titlu ?: 'Varianta ' . $loop->iteration }}
                            @if($varianta->configurator_denumire)
                                / {{ $varianta->configurator_denumire }}
                            @endif
                            @if($varianta->categorie)
                                / {{ $varianta->categorie }}
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <th>Componenta</th>
                        <th>Producator</th>
                        <th class="right">Pret</th>
                        <th class="right">Totaluri</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($varianta->componente as $componenta)
                        <tr>
                            <td>{{ $componenta->denumire }}</td>
                            <td>{{ $componenta->producator }}</td>
                            <td class="right">{{ number_format((int) $componenta->pret, 0, ',', '.') }} lei</td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="right"><b>Total calculat</b></td>
                        <td class="right">{{ number_format((int) $varianta->subtotal_calculat, 0, ',', '.') }} lei</td>
                    </tr>
                    @if(!is_null($varianta->total_manual))
                        <tr>
                            <td colspan="3" class="right"><b>Total manual</b></td>
                            <td class="right">{{ number_format((int) $varianta->total_manual, 0, ',', '.') }} lei</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="right"><b>Adaos</b></td>
                        <td class="right">{{ number_format((int) $varianta->valoare_adaos, 0, ',', '.') }} lei</td>
                    </tr>
                    @if($oferta->decontare_cas)
                        <tr>
                            <td colspan="3" class="right"><b>Buget CAS</b></td>
                            <td class="right">-{{ number_format((int) $oferta->buget_disponibil, 0, ',', '.') }} lei</td>
                        </tr>
                    @endif
                    @if($varianta->discount_valoare)
                        <tr>
                            <td colspan="3" class="right"><b>Discount {{ $varianta->discount_tip === 'procent' ? '(' . $varianta->discount_valoare . '%)' : '' }}</b></td>
                            <td class="right">-{{ number_format((int) ($varianta->discount_tip === 'procent' ? round($varianta->valoare_dupa_decontare * $varianta->discount_valoare / 100) : $varianta->discount_valoare), 0, ',', '.') }} lei</td>
                        </tr>
                    @endif
                    <tr class="total">
                        <td colspan="3" class="right">Suma de plata</td>
                        <td class="right">{{ number_format((int) $varianta->valoare_totala, 0, ',', '.') }} lei</td>
                    </tr>
                    <tr class="total">
                        <td colspan="3" class="right">Avans estimat 70%</td>
                        <td class="right">{{ number_format((int) $varianta->valoare_avans, 0, ',', '.') }} lei</td>
                    </tr>
                </tbody>
            </table>
            @if($varianta->configurator?->text_pdf)
                <p>{{ $varianta->configurator->text_pdf }}</p>
            @endif
        @endforeach
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Produs</th>
                </tr>
            </thead>
            <tbody>
                @foreach($oferta->linii as $linie)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $linie->denumire_produs }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p class="muted">
        Oferta este emisa de {{ $oferta->emitent->name ?? 'Theranova' }} si este valabila in limita termenului mentionat mai sus.
    </p>
</body>
</html>
