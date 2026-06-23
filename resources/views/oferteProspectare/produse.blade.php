@extends('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0 0;">
        <div class="col-lg-3">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-boxes-stacked me-1"></i>Produse prospectare
            </span>
        </div>
        <div class="col-lg-6">
            <form method="GET" action="{{ route('oferte-prospectare.produse.index') }}" class="row justify-content-center">
                <div class="col-lg-6">
                    <input type="text" class="form-control rounded-3" name="search" placeholder="Denumire" value="{{ $search }}">
                </div>
                <button class="btn btn-sm btn-primary text-white col-md-2 me-2 rounded-3" type="submit">Cauta</button>
                <a class="btn btn-sm btn-secondary text-white col-md-2 rounded-3" href="{{ route('oferte-prospectare.produse.index') }}">Reseteaza</a>
            </form>
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include('errors')

        <form method="POST" action="{{ route('oferte-prospectare.produse.store') }}" class="row align-items-end mb-4 px-3">
            @csrf
            <div class="col-lg-3">
                <label class="mb-0 ps-3">Denumire</label>
                <input name="denumire" class="form-control rounded-3" required>
            </div>
            <div class="col-lg-1">
                <label class="mb-0 ps-3">Cod</label>
                <input name="cod" class="form-control rounded-3">
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Pret end-user</label>
                <input name="pret_end_user" type="number" min="0" class="form-control rounded-3" required>
            </div>
            <div class="col-lg-3">
                <label class="mb-0 ps-3">Descriere</label>
                <input name="descriere" class="form-control rounded-3">
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Observatii</label>
                <input name="observatii" class="form-control rounded-3">
            </div>
            <div class="col-lg-1">
                <button class="btn btn-success text-white rounded-3" type="submit">Adauga</button>
            </div>
        </form>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-white culoare2">Denumire</th>
                        <th class="text-white culoare2">Cod</th>
                        <th class="text-white culoare2">Descriere</th>
                        <th class="text-white culoare2">Pret</th>
                        <th class="text-white culoare2">Activ</th>
                        <th class="text-white culoare2 text-end">Actiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produse as $produs)
                        <tr>
                            <td><input name="denumire" form="produs-update-{{ $produs->id }}" class="form-control rounded-3" value="{{ $produs->denumire }}" required></td>
                            <td><input name="cod" form="produs-update-{{ $produs->id }}" class="form-control rounded-3" value="{{ $produs->cod }}"></td>
                            <td><textarea name="descriere" form="produs-update-{{ $produs->id }}" class="form-control rounded-3" rows="2">{{ $produs->descriere }}</textarea></td>
                            <td><input name="pret_end_user" form="produs-update-{{ $produs->id }}" type="number" min="0" class="form-control rounded-3" value="{{ $produs->pret_end_user }}" required></td>
                            <td>
                                <select name="activ" form="produs-update-{{ $produs->id }}" class="form-select rounded-3">
                                    <option value="1" {{ $produs->activ ? 'selected' : '' }}>DA</option>
                                    <option value="0" {{ !$produs->activ ? 'selected' : '' }}>NU</option>
                                </select>
                                <input type="hidden" name="observatii" form="produs-update-{{ $produs->id }}" value="{{ $produs->observatii }}">
                            </td>
                            <td class="text-end">
                                <form id="produs-update-{{ $produs->id }}" method="POST" action="{{ route('oferte-prospectare.produse.update', $produs) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-primary text-white rounded-3" type="submit">Salveaza</button>
                                </form>
                                <form method="POST" action="{{ route('oferte-prospectare.produse.destroy', $produs) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white rounded-3" type="submit">Dezactiveaza</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nu exista produse.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $produse->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
