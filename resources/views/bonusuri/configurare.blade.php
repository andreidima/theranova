@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-6">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-sliders me-1"></i>Configurare Bonusuri
            </span>
        </div>
        <div class="col-lg-6 text-end">
            <button class="btn btn-sm btn-primary rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#adaugaLucrareModal">
                Adauga lucrare
            </button>
            <button class="btn btn-sm btn-warning rounded-3 border border-dark" data-bs-toggle="modal" data-bs-target="#adaugaIntervalModal">
                Adauga interval bonus
            </button>
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <div class="alert alert-info mb-3">
            <strong>Activa</strong>: lucrarea poate fi folosita pentru configurare intervale si calcule noi de bonus.
        </div>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Lucrare</th>
                        <th class="culoare2 text-white">Cod</th>
                        <th class="culoare2 text-white">Activa</th>
                        <th class="culoare2 text-white">Dependente</th>
                        <th class="culoare2 text-white">Intervale bonus</th>
                        <th class="culoare2 text-white text-end">Actiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lucrari as $lucrare)
                        @php
                            $poateFiStearsa = ((int) $lucrare->fise_caz_count === 0) && ((int) $lucrare->bonusuri_count === 0);
                        @endphp
                        <tr>
                            <td>{{ $lucrare->denumire }}</td>
                            <td><span class="fw-bold">{{ $lucrare->cod }}</span></td>
                            <td>{{ $lucrare->activ ? 'Da' : 'Nu' }}</td>
                            <td>
                                Fișe caz: <strong>{{ $lucrare->fise_caz_count }}</strong><br>
                                Bonusuri: <strong>{{ $lucrare->bonusuri_count }}</strong>
                            </td>
                            <td style="min-width: 320px;" class="align-middle">
                                @forelse($lucrare->intervaleBonus as $interval)
                                    @php
                                        $poateFiStersInterval = ((int) $interval->bonusuri_count === 0);
                                    @endphp
                                    <div class="border rounded-3 p-2 mb-2">
                                        <div class="small">
                                            Interval valoare:
                                            {{ (int) $interval->min_valoare }}
                                            -
                                            {{ is_null($interval->max_valoare) ? 'INF' : (int) $interval->max_valoare }} lei
                                        </div>
                                        <div class="small text-muted d-inline-flex align-items-center flex-nowrap gap-1">
                                            <span class="text-nowrap">
                                                Fix: {{ (int) $interval->bonus_fix }} |
                                                %: {{ (int) $interval->bonus_procent }} |
                                                {{ $interval->activ ? 'Activ' : 'Inactiv' }}
                                            </span>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary py-0 px-1 flex-shrink-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modificaInterval{{ $interval->id }}"
                                                title="Modifica interval"
                                                >
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger py-0 px-1 flex-shrink-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#{{ $poateFiStersInterval ? 'stergeInterval' . $interval->id : 'stergeIntervalBlocat' . $interval->id }}"
                                                title="Sterge interval"
                                                >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <span class="text-muted">Fara intervale definite.</span>
                                @endforelse
                            </td>
                            <td class="align-top text-end">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-0"
                                        style="width:30px;height:30px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modificaLucrare{{ $lucrare->id }}"
                                        title="Modifica lucrare"
                                        >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0"
                                        style="width:30px;height:30px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $poateFiStearsa ? 'stergeLucrare' . $lucrare->id : 'stergeLucrareBlocata' . $lucrare->id }}"
                                        title="Sterge lucrare"
                                        >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nu exista lucrari.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade text-dark" id="adaugaLucrareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('bonusuri.configurare.lucrari.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Adauga lucrare</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label mb-1">Denumire</label>
                        <input type="text" class="form-control rounded-3" name="denumire" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-1">Cod (optional)</label>
                        <input type="text" class="form-control rounded-3" name="cod">
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="hidden" name="activ" value="0">
                        <input class="form-check-input" type="checkbox" value="1" id="activLucrareNoua" name="activ" checked>
                        <label class="form-check-label" for="activLucrareNoua">Activa</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunta</button>
                    <button type="submit" class="btn btn-primary">Salveaza</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade text-dark" id="adaugaIntervalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('bonusuri.configurare.intervale.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Adauga interval bonus</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label mb-1">Lucrare</label>
                        <select name="lucrare_id" class="form-select rounded-3" required>
                            <option value="">Alege</option>
                            @foreach($lucrariActive as $lucrare)
                                <option value="{{ $lucrare->id }}">{{ $lucrare->denumire }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label mb-1">Min valoare</label>
                            <input type="number" step="1" min="0" name="min_valoare" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1">Max valoare</label>
                            <input type="number" step="1" min="0" name="max_valoare" class="form-control rounded-3">
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1">Bonus fix</label>
                            <input type="number" step="1" min="0" name="bonus_fix" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1">Bonus procent</label>
                            <input type="number" step="1" min="0" max="100" name="bonus_procent" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1">Valid de la</label>
                            <input type="date" name="valid_from" class="form-control rounded-3">
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1">Valid pana la</label>
                            <input type="date" name="valid_to" class="form-control rounded-3">
                        </div>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="hidden" name="activ" value="0">
                        <input class="form-check-input" type="checkbox" value="1" id="activIntervalNou" name="activ" checked>
                        <label class="form-check-label" for="activIntervalNou">Activ</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunta</button>
                    <button type="submit" class="btn btn-warning border border-dark">Salveaza</button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach($lucrari as $lucrare)
    <div class="modal fade text-dark" id="modificaLucrare{{ $lucrare->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('bonusuri.configurare.lucrari.update', $lucrare) }}">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Modifica lucrare</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label mb-1">Denumire</label>
                            <input type="text" class="form-control rounded-3" name="denumire" value="{{ $lucrare->denumire }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1">Cod</label>
                            <input type="text" class="form-control rounded-3" name="cod" value="{{ $lucrare->cod }}" required>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="hidden" name="activ" value="0">
                            <input class="form-check-input" type="checkbox" value="1" id="activLucrare{{ $lucrare->id }}" name="activ" {{ $lucrare->activ ? 'checked' : '' }}>
                            <label class="form-check-label" for="activLucrare{{ $lucrare->id }}">Activa</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunta</button>
                        <button type="submit" class="btn btn-primary">Salveaza</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade text-dark" id="stergeLucrare{{ $lucrare->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('bonusuri.configurare.lucrari.delete', $lucrare) }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white">Sterge lucrare</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Confirmi stergerea lucrarii <strong>{{ $lucrare->denumire }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunta</button>
                        <button type="submit" class="btn btn-danger">Sterge</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade text-dark" id="stergeLucrareBlocata{{ $lucrare->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Lucrarea nu poate fi stearsa</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Lucrarea <strong>{{ $lucrare->denumire }}</strong> este blocata pentru stergere.<br>
                    Fișe caz: <strong>{{ $lucrare->fise_caz_count }}</strong><br>
                    Bonusuri: <strong>{{ $lucrare->bonusuri_count }}</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
                </div>
            </div>
        </div>
    </div>

    @foreach($lucrare->intervaleBonus as $interval)
        <div class="modal fade text-dark" id="modificaInterval{{ $interval->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('bonusuri.configurare.intervale.update', $interval) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Modifica interval bonus</h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label class="form-label mb-1">Lucrare</label>
                                <input type="text" class="form-control rounded-3" value="{{ $lucrare->denumire }}" disabled>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label mb-1">Min valoare</label>
                                    <input type="number" step="1" min="0" name="min_valoare" value="{{ $interval->min_valoare }}" class="form-control rounded-3" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mb-1">Max valoare</label>
                                    <input type="number" step="1" min="0" name="max_valoare" value="{{ $interval->max_valoare }}" class="form-control rounded-3">
                                </div>
                                <div class="col-6">
                                    <label class="form-label mb-1">Bonus fix</label>
                                    <input type="number" step="1" min="0" name="bonus_fix" value="{{ $interval->bonus_fix }}" class="form-control rounded-3" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mb-1">Bonus procent</label>
                                    <input type="number" step="1" min="0" max="100" name="bonus_procent" value="{{ $interval->bonus_procent }}" class="form-control rounded-3" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mb-1">Valid de la</label>
                                    <input type="date" name="valid_from" value="{{ optional($interval->valid_from)->format('Y-m-d') }}" class="form-control rounded-3">
                                </div>
                                <div class="col-6">
                                    <label class="form-label mb-1">Valid pana la</label>
                                    <input type="date" name="valid_to" value="{{ optional($interval->valid_to)->format('Y-m-d') }}" class="form-control rounded-3">
                                </div>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="hidden" name="activ" value="0">
                                <input class="form-check-input" type="checkbox" value="1" id="activInterval{{ $interval->id }}" name="activ" {{ $interval->activ ? 'checked' : '' }}>
                                <label class="form-check-label" for="activInterval{{ $interval->id }}">Activ</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunta</button>
                            <button type="submit" class="btn btn-warning border border-dark">Salveaza</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade text-dark" id="stergeInterval{{ $interval->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('bonusuri.configurare.intervale.delete', $interval) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white">Sterge interval bonus</h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Confirmi stergerea intervalului
                            <strong>{{ (int) $interval->min_valoare }} - {{ is_null($interval->max_valoare) ? 'INF' : (int) $interval->max_valoare }} lei</strong>
                            pentru lucrarea <strong>{{ $lucrare->denumire }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunta</button>
                            <button type="submit" class="btn btn-danger">Sterge</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade text-dark" id="stergeIntervalBlocat{{ $interval->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Intervalul nu poate fi sters</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Intervalul
                        <strong>{{ (int) $interval->min_valoare }} - {{ is_null($interval->max_valoare) ? 'INF' : (int) $interval->max_valoare }} lei</strong>
                        este folosit in <strong>{{ (int) $interval->bonusuri_count }}</strong> bonus(uri).
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
@endsection
