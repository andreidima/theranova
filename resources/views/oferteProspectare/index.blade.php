@extends('layouts.app')

@php
    use App\Models\OfertaProspectare;
@endphp

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0 0;">
        <div class="col-lg-3">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-handshake me-1"></i>Oferte prospectare
            </span>
        </div>
        <div class="col-lg-6">
            <form method="GET" action="{{ route('oferte-prospectare.index') }}">
                <div class="row mb-1 custom-search-form justify-content-center">
                    <div class="col-lg-4">
                        <input type="text" class="form-control rounded-3" name="search" placeholder="Client / telefon / email" value="{{ $search }}">
                    </div>
                    <div class="col-lg-2">
                        <input type="text" class="form-control rounded-3" name="judet" placeholder="Judet" value="{{ $judet }}">
                    </div>
                    <div class="col-lg-3">
                        <select name="status_aprobare" class="form-select rounded-3">
                            <option value="">Status intern</option>
                            @foreach (OfertaProspectare::statusuriAprobare() as $key => $label)
                                <option value="{{ $key }}" {{ $statusAprobare === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <select name="status_client" class="form-select rounded-3">
                            <option value="">Status client</option>
                            @foreach (OfertaProspectare::statusuriClient() as $key => $label)
                                <option value="{{ $key }}" {{ $statusClient === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row custom-search-form justify-content-center">
                    <div class="col-lg-4 mb-1">
                        <select name="user_emitent_id" class="form-select rounded-3">
                            <option value="">Emitent</option>
                            @foreach ($useri as $user)
                                <option value="{{ $user->id }}" {{ (int) $userEmitentId === (int) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-sm btn-primary text-white col-md-3 me-3 border border-dark rounded-3 d-inline-flex align-items-center justify-content-center py-1" type="submit">
                        <i class="fas fa-search text-white me-1"></i>Cauta
                    </button>
                    <a class="btn btn-sm btn-secondary text-white col-md-3 border border-dark rounded-3 d-inline-flex align-items-center justify-content-center py-1" href="{{ route('oferte-prospectare.index') }}">
                        <i class="far fa-trash-alt text-white me-1"></i>Reseteaza
                    </a>
                </div>
            </form>
        </div>
        <div class="col-lg-3 text-end">
            <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="{{ route('oferte-prospectare.create') }}">
                <i class="fas fa-plus-square text-white me-1"></i>Adauga oferta
            </a>
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include('errors')

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-white culoare2">#</th>
                        <th class="text-white culoare2">Client</th>
                        <th class="text-white culoare2">Contact</th>
                        <th class="text-white culoare2">Judet</th>
                        <th class="text-white culoare2">Valoare</th>
                        <th class="text-white culoare2">Status intern</th>
                        <th class="text-white culoare2">Status client</th>
                        <th class="text-white culoare2">Emitent</th>
                        <th class="text-white culoare2 text-end">Actiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($oferte as $oferta)
                        <tr>
                            <td>{{ ($oferte->currentpage() - 1) * $oferte->perpage() + $loop->index + 1 }}</td>
                            <td>
                                <b>{{ $oferta->nume_client }}</b>
                                <br>
                                <small>Oferta #{{ $oferta->id }} / {{ optional($oferta->data_ofertei)->format('d.m.Y') }}</small>
                            </td>
                            <td>
                                {{ $oferta->telefon }}
                                @if ($oferta->email)
                                    <br><small>{{ $oferta->email }}</small>
                                @endif
                            </td>
                            <td>{{ $oferta->judet }}<br><small>{{ $oferta->localitate }}</small></td>
                            <td>{{ number_format((int) $oferta->valoare_totala, 0, ',', '.') }} lei</td>
                            <td>{{ OfertaProspectare::statusuriAprobare()[$oferta->status_aprobare] ?? $oferta->status_aprobare }}</td>
                            <td>{{ OfertaProspectare::statusuriClient()[$oferta->status_client] ?? $oferta->status_client }}</td>
                            <td>{{ $oferta->emitent->name ?? '' }}</td>
                            <td class="text-end">
                                <a href="{{ $oferta->path() }}"><span class="badge bg-success">Vizualizeaza</span></a>
                                <a href="{{ $oferta->path() }}/modifica"><span class="badge bg-primary">Modifica</span></a>
                                <a href="{{ route('oferte-prospectare.pdf', $oferta) }}" target="_blank"><span class="badge bg-secondary">PDF</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Nu exista oferte de prospectare.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $oferte->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
