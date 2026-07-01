@extends('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0 0;">
        <div class="col-lg-3">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-users me-1"></i>Clienti prospectare
            </span>
        </div>
        <div class="col-lg-6">
            <form method="GET" action="{{ route('oferte-prospectare.clienti.index') }}" class="row justify-content-center">
                <div class="col-lg-6">
                    <input type="text" class="form-control rounded-3" name="search" placeholder="Nume / telefon / email" value="{{ $search }}">
                </div>
                <button class="btn btn-sm btn-primary text-white col-md-2 me-2 rounded-3 d-inline-flex align-items-center justify-content-center py-0" style="line-height: 1.2;" type="submit">Cauta</button>
                <a class="btn btn-sm btn-secondary text-white col-md-2 rounded-3 d-inline-flex align-items-center justify-content-center py-0" style="line-height: 1.2;" href="{{ route('oferte-prospectare.clienti.index') }}">Reseteaza</a>
            </form>
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include('errors')

        <form method="POST" action="{{ route('oferte-prospectare.clienti.store') }}" class="row align-items-end mb-4 px-3">
            @csrf
            <div class="col-lg-3">
                <label class="mb-0 ps-3">Nume</label>
                <input name="nume" class="form-control rounded-3" required>
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Telefon</label>
                <input name="telefon" class="form-control rounded-3">
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Email</label>
                <input name="email" type="email" class="form-control rounded-3">
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Sursa</label>
                <select name="sursa" class="form-select rounded-3">
                    <option value=""></option>
                    @foreach($surseProspectare as $sursa)
                        <option value="{{ $sursa }}">{{ $sursa }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Judet</label>
                <select name="judet" class="form-select rounded-3">
                    <option value=""></option>
                    @foreach($judeteRomania as $judet)
                        <option value="{{ $judet }}">{{ $judet }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1">
                <button class="btn btn-success text-white rounded-3" type="submit">Adauga</button>
            </div>
        </form>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-white culoare2">Nume</th>
                        <th class="text-white culoare2">Telefon</th>
                        <th class="text-white culoare2">Email</th>
                        <th class="text-white culoare2">Localitate</th>
                        <th class="text-white culoare2">Judet</th>
                        <th class="text-white culoare2">Sursa</th>
                        <th class="text-white culoare2">Activ</th>
                        <th class="text-white culoare2 text-end">Actiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clienti as $client)
                        <tr>
                            <td><input name="nume" form="client-update-{{ $client->id }}" class="form-control rounded-3" value="{{ $client->nume }}" required></td>
                            <td><input name="telefon" form="client-update-{{ $client->id }}" class="form-control rounded-3" value="{{ $client->telefon }}"></td>
                            <td><input name="email" form="client-update-{{ $client->id }}" type="email" class="form-control rounded-3" value="{{ $client->email }}"></td>
                            <td><input name="localitate" form="client-update-{{ $client->id }}" class="form-control rounded-3" value="{{ $client->localitate }}"></td>
                            <td>
                                <select name="judet" form="client-update-{{ $client->id }}" class="form-select rounded-3">
                                    <option value=""></option>
                                    @foreach($judeteRomania as $judet)
                                        <option value="{{ $judet }}" {{ $client->judet === $judet ? 'selected' : '' }}>{{ $judet }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="sursa" form="client-update-{{ $client->id }}" class="form-select rounded-3">
                                    <option value=""></option>
                                    @foreach($surseProspectare as $sursa)
                                        <option value="{{ $sursa }}" {{ $client->sursa === $sursa ? 'selected' : '' }}>{{ $sursa }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="activ" form="client-update-{{ $client->id }}" class="form-select rounded-3">
                                    <option value="1" {{ $client->activ ? 'selected' : '' }}>DA</option>
                                    <option value="0" {{ !$client->activ ? 'selected' : '' }}>NU</option>
                                </select>
                            </td>
                            <td class="text-end">
                                <form id="client-update-{{ $client->id }}" method="POST" action="{{ route('oferte-prospectare.clienti.update', $client) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-primary text-white rounded-3" type="submit">Salveaza</button>
                                </form>
                                <form method="POST" action="{{ route('oferte-prospectare.clienti.destroy', $client) }}" class="d-inline" onsubmit="return confirm('Sigur vrei sa dezactivezi acest client?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white rounded-3" type="submit">Dezactiveaza</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Nu exista clienti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $clienti->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
