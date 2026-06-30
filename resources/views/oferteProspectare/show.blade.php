@extends('layouts.app')

@php
    use App\Models\OfertaProspectare;

    $statusAprobare = OfertaProspectare::statusuriAprobare();
    $statusClient = OfertaProspectare::statusuriClient();
    $canSend = $oferta->status_aprobare === OfertaProspectare::APROBARE_APROBATA;
@endphp

@section('content')
<div class="container-fluid px-4">
    <div class="shadow-lg" style="border-radius: 40px;">
        <div class="border border-secondary p-2 culoare2 d-flex justify-content-between align-items-center" style="border-radius: 40px 40px 0 0;">
            <span class="badge text-light fs-5">
                <i class="fa-solid fa-handshake me-1"></i>Oferta prospectare #{{ $oferta->id }}
            </span>
            <div>
                <a class="btn btn-sm btn-light rounded-3" href="{{ route('oferte-prospectare.pdf', $oferta) }}" target="_blank">PDF</a>
                <a class="btn btn-sm btn-primary text-white rounded-3" href="{{ $oferta->path() }}/modifica">Modifica</a>
                @if($canApprove || auth()->user()->hasRole('stergere'))
                    <form method="POST" action="{{ $oferta->path() }}" class="d-inline" onsubmit="return confirm('Sigur vrei sa stergi aceasta oferta de prospectare?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger text-white rounded-3">Sterge</button>
                    </form>
                @endif
                <a class="btn btn-sm btn-secondary rounded-3" href="{{ Session::get('ofertaProspectareReturnUrl') ?? route('oferte-prospectare.index') }}">Inapoi</a>
            </div>
        </div>

        <div class="card-body py-3 border border-secondary" style="border-radius: 0 0 40px 40px;">
            @include('errors')

            <div class="row">
                <div class="col-lg-8">
                    <div class="table-responsive rounded-3 mb-3">
                        <table class="table table-striped">
                            <tr>
                                <th class="w-25">Client</th>
                                <td>{{ $oferta->nume_client }}</td>
                                <th>Telefon</th>
                                <td>{{ $oferta->telefon }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $oferta->email }}</td>
                                <th>Localitate / judet</th>
                                <td>{{ $oferta->localitate }} / {{ $oferta->judet }}</td>
                            </tr>
                            <tr>
                                <th>Emitent</th>
                                <td>{{ $oferta->emitent->name ?? '' }}</td>
                                <th>Aprobator</th>
                                <td>{{ $oferta->aprobator->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Data oferta</th>
                                <td>{{ optional($oferta->data_ofertei)->format('d.m.Y') }}</td>
                                <th>Valabila pana la</th>
                                <td>{{ optional($oferta->valabila_pana_la)->format('d.m.Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status intern</th>
                                <td>{{ $statusAprobare[$oferta->status_aprobare] ?? $oferta->status_aprobare }}</td>
                                <th>Status client</th>
                                <td>{{ $statusClient[$oferta->status_client] ?? $oferta->status_client }}</td>
                            </tr>
                            <tr>
                                <th>Tip lucrare</th>
                                <td>{{ $oferta->tip_lucrare_solicitata }}</td>
                                <th>CAS</th>
                                <td>{{ $oferta->decontare_cas ? 'DA' : 'NU' }} {{ $oferta->buget_disponibil ? '(' . number_format($oferta->buget_disponibil, 0, ',', '.') . ' lei)' : '' }}</td>
                            </tr>
                            <tr>
                                <th>Date amputatie</th>
                                <td colspan="3">
                                    @forelse($oferta->amputatii as $amputatie)
                                        <div>{{ $amputatie->amputatie }} {{ $amputatie->parte_amputata }}</div>
                                    @empty
                                        {{ $oferta->amputatie }} {{ $oferta->parte_amputata }}
                                    @endforelse
                                    @if($oferta->nivel_de_activitate)
                                        <div>Nivel {{ $oferta->nivel_de_activitate }}</div>
                                    @endif
                                </td>
                            </tr>
                            @if($oferta->observatii_interne || $oferta->observatii_admin)
                                <tr>
                                    <th>Observatii interne</th>
                                    <td colspan="3">
                                        {{ $oferta->observatii_interne }}
                                        @if($oferta->observatii_admin)
                                            <hr>
                                            <b>Admin:</b> {{ $oferta->observatii_admin }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if($oferta->fisaCaz)
                                <tr>
                                    <th>Conversie</th>
                                    <td colspan="3">
                                        <a href="{{ $oferta->fisaCaz->path() }}">Fisa caz #{{ $oferta->fisaCaz->id }}</a>
                                        / <a href="{{ $oferta->pacient->path() ?? '#' }}">{{ $oferta->pacient->nume ?? '' }} {{ $oferta->pacient->prenume ?? '' }}</a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>

                    <div class="table-responsive rounded-3 mb-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-white culoare2">#</th>
                                    <th class="text-white culoare2">Produs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oferta->linii as $linie)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $linie->denumire_produs }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th class="text-end">Total oferta</th>
                                    <th>{{ number_format((int) ($oferta->total_oferta ?? $oferta->subtotal), 0, ',', '.') }} lei</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Adaos</th>
                                    <th>{{ number_format((float) ($oferta->procent_adaos ?? 0), 2, ',', '.') }}% / {{ number_format((int) ($oferta->valoare_adaos ?? 0), 0, ',', '.') }} lei</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Total cu adaos</th>
                                    <th>{{ number_format((int) (($oferta->total_oferta ?? $oferta->subtotal) + ($oferta->valoare_adaos ?? 0)), 0, ',', '.') }} lei</th>
                                </tr>
                                @if($oferta->decontare_cas)
                                    <tr>
                                        <th class="text-end">Buget CAS</th>
                                        <th>-{{ number_format((int) $oferta->buget_disponibil, 0, ',', '.') }} lei</th>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-end">Discount</th>
                                    <th>{{ number_format((int) $oferta->discount_aditional, 0, ',', '.') }} lei</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Suma de plata</th>
                                    <th>{{ number_format((int) $oferta->valoare_totala, 0, ',', '.') }} lei</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Avans 70%</th>
                                    <th>{{ number_format((int) $oferta->valoare_avans, 0, ',', '.') }} lei</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="border rounded-3 p-3 mb-3">
                        <h5>Flux intern</h5>
                        <form method="POST" action="{{ route('oferte-prospectare.submit', $oferta) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-warning w-100 rounded-3" type="submit">Trimite la aprobare</button>
                        </form>

                        @if($canApprove)
                            <form method="POST" action="{{ route('oferte-prospectare.approve', $oferta) }}" class="mb-2">
                                @csrf
                                <textarea name="observatii_admin" class="form-control rounded-3 mb-2" rows="2" placeholder="Observatii admin">{{ old('observatii_admin') }}</textarea>
                                <button class="btn btn-success text-white w-100 rounded-3" type="submit">Aproba intern</button>
                            </form>
                            <form method="POST" action="{{ route('oferte-prospectare.request-changes', $oferta) }}" class="mb-2">
                                @csrf
                                <textarea name="observatii_admin" class="form-control rounded-3 mb-2" rows="2" placeholder="Ce trebuie modificat?"></textarea>
                                <button class="btn btn-primary text-white w-100 rounded-3" type="submit">Cere modificari</button>
                            </form>
                            <form method="POST" action="{{ route('oferte-prospectare.reject', $oferta) }}">
                                @csrf
                                <textarea name="observatii_admin" class="form-control rounded-3 mb-2" rows="2" placeholder="Motiv respingere"></textarea>
                                <button class="btn btn-danger text-white w-100 rounded-3" type="submit">Respinge intern</button>
                            </form>
                        @endif
                    </div>

                    <div class="border rounded-3 p-3 mb-3">
                        <h5>Trimitere client</h5>
                        @if(!$canSend)
                            <div class="alert alert-warning">Oferta trebuie aprobata intern inainte de trimitere.</div>
                        @endif
                        <form method="POST" action="{{ route('oferte-prospectare.send-email', $oferta) }}" class="mb-2">
                            @csrf
                            <textarea name="mesaj" class="form-control rounded-3 mb-2" rows="3" placeholder="Mesaj email optional"></textarea>
                            <button class="btn btn-success text-white w-100 rounded-3" type="submit" {{ !$canSend ? 'disabled' : '' }}>Trimite email cu PDF</button>
                        </form>
                        <form method="POST" action="{{ route('oferte-prospectare.whatsapp', $oferta) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-primary text-white w-100 rounded-3" type="submit" {{ !$canSend ? 'disabled' : '' }}>Deschide WhatsApp</button>
                        </form>
                    </div>

                    <div class="border rounded-3 p-3 mb-3">
                        <h5>Status client</h5>
                        <form method="POST" action="{{ route('oferte-prospectare.client-status', $oferta) }}">
                            @csrf
                            <select name="status_client" class="form-select rounded-3 mb-2">
                                @foreach($statusClient as $key => $label)
                                    <option value="{{ $key }}" {{ $oferta->status_client === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary text-white w-100 rounded-3" type="submit">Actualizeaza status</button>
                        </form>
                    </div>

                    @if(!$oferta->fisa_caz_id)
                        <div class="border rounded-3 p-3 mb-3">
                            <h5>Conversie in fisa caz</h5>
                            <form method="POST" action="{{ route('oferte-prospectare.convert', $oferta) }}">
                                @csrf
                                @php
                                    $nameParts = explode(' ', trim($oferta->nume_client), 2);
                                @endphp
                                <label class="mb-0 ps-3">Nume</label>
                                <input name="pacient_nume" class="form-control rounded-3 mb-2" value="{{ old('pacient_nume', $nameParts[0] ?? '') }}">
                                <label class="mb-0 ps-3">Prenume</label>
                                <input name="pacient_prenume" class="form-control rounded-3 mb-2" value="{{ old('pacient_prenume', $nameParts[1] ?? '') }}">
                                <label class="mb-0 ps-3">Tip lucrare</label>
                                <input name="tip_lucrare_solicitata" class="form-control rounded-3 mb-2" value="{{ old('tip_lucrare_solicitata', $oferta->tip_lucrare_solicitata) }}">
                                <button class="btn btn-warning w-100 rounded-3" type="submit">Converteste</button>
                            </form>
                        </div>
                    @endif

                    <div class="border rounded-3 p-3">
                        <h5>Istoric trimiteri</h5>
                        @forelse($oferta->trimiteri as $trimitere)
                            <div class="border-bottom py-2">
                                <b>{{ $trimitere->canal }}</b> catre {{ $trimitere->destinatar }}
                                <br>
                                <small>{{ optional($trimitere->created_at)->format('d.m.Y H:i') }} / {{ $trimitere->user->name ?? '' }}</small>
                            </div>
                        @empty
                            <p class="mb-0">Nu exista trimiteri.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
