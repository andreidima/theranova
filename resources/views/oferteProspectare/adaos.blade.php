@extends('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0 0;">
        <div class="col-lg-8">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-percent me-1"></i>Adaos ofertare prospectare
            </span>
        </div>
        <div class="col-lg-4 text-end">
            <a class="btn btn-sm btn-secondary text-white rounded-3" href="{{ route('oferte-prospectare.index') }}">Inapoi la oferte</a>
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include('errors')

        <form method="POST" action="{{ route('oferte-prospectare.adaos.store') }}" class="row align-items-end mb-4 px-3">
            @csrf
            <div class="col-lg-3">
                <label class="mb-0 ps-3">Valoare minima</label>
                <input name="valoare_min" type="number" min="0" class="form-control rounded-3" value="{{ old('valoare_min') }}" required>
            </div>
            <div class="col-lg-3">
                <label class="mb-0 ps-3">Valoare maxima</label>
                <input name="valoare_max" type="number" min="0" class="form-control rounded-3" value="{{ old('valoare_max') }}" placeholder="Fara limita">
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Adaos %</label>
                <input name="procent" type="number" min="0" max="100" step="0.01" class="form-control rounded-3" value="{{ old('procent') }}" required>
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Activ</label>
                <select name="activ" class="form-select rounded-3">
                    <option value="1" {{ old('activ', '1') === '1' ? 'selected' : '' }}>DA</option>
                    <option value="0" {{ old('activ') === '0' ? 'selected' : '' }}>NU</option>
                </select>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-success text-white rounded-3" type="submit">Adauga</button>
            </div>
        </form>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-white culoare2">Valoare minima</th>
                        <th class="text-white culoare2">Valoare maxima</th>
                        <th class="text-white culoare2">Adaos %</th>
                        <th class="text-white culoare2">Activ</th>
                        <th class="text-white culoare2 text-end">Actiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($intervale as $interval)
                        <tr>
                            <td>
                                <input name="valoare_min" form="adaos-update-{{ $interval->id }}" type="number" min="0" class="form-control rounded-3" value="{{ $interval->valoare_min }}" required>
                            </td>
                            <td>
                                <input name="valoare_max" form="adaos-update-{{ $interval->id }}" type="number" min="0" class="form-control rounded-3" value="{{ $interval->valoare_max }}" placeholder="Fara limita">
                            </td>
                            <td>
                                <input name="procent" form="adaos-update-{{ $interval->id }}" type="number" min="0" max="100" step="0.01" class="form-control rounded-3" value="{{ $interval->procent }}" required>
                            </td>
                            <td>
                                <select name="activ" form="adaos-update-{{ $interval->id }}" class="form-select rounded-3">
                                    <option value="1" {{ $interval->activ ? 'selected' : '' }}>DA</option>
                                    <option value="0" {{ !$interval->activ ? 'selected' : '' }}>NU</option>
                                </select>
                            </td>
                            <td class="text-end">
                                <form id="adaos-update-{{ $interval->id }}" method="POST" action="{{ route('oferte-prospectare.adaos.update', $interval) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-primary text-white rounded-3" type="submit">Salveaza</button>
                                </form>
                                <form method="POST" action="{{ route('oferte-prospectare.adaos.destroy', $interval) }}" class="d-inline" onsubmit="return confirm('Sigur vrei sa stergi acest interval de adaos?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white rounded-3" type="submit">Sterge</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nu exista intervale de adaos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $intervale->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
