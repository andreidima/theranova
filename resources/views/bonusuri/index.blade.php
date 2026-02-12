@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-6">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-coins me-1"></i>Bonusuri - Lunar
            </span>
        </div>
        <div class="col-lg-6 text-end">
            @if($canEdit)
                <form method="POST" action="{{ route('bonusuri.calculeaza') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning border border-dark rounded-3">
                        Calculeaza bonusuri eligibile
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <form method="GET" action="{{ route('bonusuri.index') }}" class="row g-2 align-items-end mb-3">
            <div class="col-lg-2">
                <label class="form-label mb-1">Luna</label>
                <input type="month" name="month" class="form-control rounded-3" value="{{ $month }}">
            </div>
            @if($canViewAll)
                <div class="col-lg-3">
                    <label class="form-label mb-1">Utilizator</label>
                    <select name="user_id" class="form-select rounded-3">
                        <option value="">Toti</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (string) $selectedUserId === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-lg-3">
                <button class="btn btn-primary rounded-3" type="submit">Filtreaza</button>
            </div>
        </form>

        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="border rounded-3 p-3 bg-light">
                    <div><strong>Total bonusuri luna:</strong> {{ number_format((int) $sumar['total_bonus'], 0, ',', '.') }} lei</div>
                    <div><strong>Total platite:</strong> {{ number_format((int) $sumar['total_platite'], 0, ',', '.') }} lei</div>
                    <div><strong>Total neplatite:</strong> {{ number_format((int) $sumar['total_neplatite'], 0, ',', '.') }} lei</div>
                </div>
            </div>
        </div>

        <div class="table-responsive rounded-3 mb-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Pacient / Fisa</th>
                        <th class="culoare2 text-white">Utilizator</th>
                        <th class="culoare2 text-white">Rol</th>
                        <th class="culoare2 text-white">Lucrare</th>
                        <th class="culoare2 text-white">Oferta</th>
                        <th class="culoare2 text-white">Bonus</th>
                        <th class="culoare2 text-white">Status</th>
                        <th class="culoare2 text-white">Luna merit</th>
                        <th class="culoare2 text-white">Data plata</th>
                        @if($canEdit)
                            <th class="culoare2 text-white">Editare</th>
                        @endif
                        <th class="culoare2 text-white">Istoric</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bonusuri as $bonus)
                        <tr>
                            <td>
                                <div>
                                    {{ $bonus->fisaCaz->pacient->nume ?? '' }} {{ $bonus->fisaCaz->pacient->prenume ?? '' }}
                                </div>
                                <div>
                                    <a href="/fise-caz/{{ $bonus->fisa_caz_id }}" target="_blank">Fișa #{{ $bonus->fisa_caz_id }}</a>
                                </div>
                            </td>
                            <td>{{ $bonus->user->name ?? '-' }}</td>
                            <td>{{ ucfirst($bonus->rol_in_fisa) }}</td>
                            <td>{{ $bonus->lucrare->denumire ?? '-' }}</td>
                            <td>{{ number_format((int) $bonus->valoare_oferta, 0, ',', '.') }} lei</td>
                            <td>
                                {{ number_format((int) $bonus->bonus_total, 0, ',', '.') }} lei
                                <div class="small text-muted">
                                    Fix: {{ (int) $bonus->bonus_fix }},
                                    %: {{ (int) $bonus->bonus_procent }}
                                </div>
                            </td>
                            <td>
                                @if($bonus->status === \App\Models\Bonus::STATUS_PLATIT)
                                    <span class="badge bg-success">platit</span>
                                @elseif($bonus->status === \App\Models\Bonus::STATUS_APROBAT)
                                    <span class="badge bg-info text-dark">aprobat</span>
                                @elseif($bonus->status === \App\Models\Bonus::STATUS_ANULAT)
                                    <span class="badge bg-danger">anulat</span>
                                @else
                                    <span class="badge bg-secondary">calculat</span>
                                @endif
                            </td>
                            <td>{{ optional($bonus->luna_merit)->format('m.Y') }}</td>
                            <td>{{ optional($bonus->data_plata)->format('d.m.Y') }}</td>
                            @if($canEdit)
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modificaBonus{{ $bonus->id }}"
                                        >
                                        Modifica bonus
                                    </button>
                                </td>
                            @endif
                            <td style="min-width: 250px;">
                                @forelse($bonus->istoric as $istoric)
                                    <div class="small border-bottom pb-1 mb-1">
                                        <div><strong>{{ $istoric->created_at?->format('d.m.Y H:i') }}</strong> - {{ $istoric->actiune }}</div>
                                        <div>Status: {{ $istoric->status ?? '-' }}</div>
                                        <div>Bonus: {{ is_null($istoric->bonus_total) ? '-' : number_format((int) $istoric->bonus_total, 0, ',', '.') }}</div>
                                        <div>Data plata: {{ optional($istoric->data_plata)->format('d.m.Y') ?? '-' }}</div>
                                        <div>{{ $istoric->detalii ?? '' }}</div>
                                        <div class="text-muted">{{ $istoric->user->name ?? '-' }}</div>
                                    </div>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canEdit ? '11' : '10' }}" class="text-center">Nu exista bonusuri in perioada selectata.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($canEdit)
            @foreach($bonusuri as $bonus)
                <div class="modal fade text-dark" id="modificaBonus{{ $bonus->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('bonusuri.actualizeaza', $bonus) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white">Modifica bonus #{{ $bonus->id }}</h5>
                                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label class="form-label mb-1">Status</label>
                                        <select name="status" class="form-select rounded-3">
                                            @foreach($statusuri as $status)
                                                <option value="{{ $status }}" {{ $bonus->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <label class="form-label mb-1">Bonus fix</label>
                                            <input type="number" step="1" min="0" name="bonus_fix" value="{{ $bonus->bonus_fix }}" class="form-control rounded-3">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label mb-1">Bonus %</label>
                                            <input type="number" step="1" min="0" max="100" name="bonus_procent" value="{{ $bonus->bonus_procent }}" class="form-control rounded-3">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label mb-1">Bonus total</label>
                                            <input type="number" step="1" min="0" name="bonus_total" value="{{ $bonus->bonus_total }}" class="form-control rounded-3">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mb-1">Data plata</label>
                                            <input type="date" name="data_plata" value="{{ optional($bonus->data_plata)->format('Y-m-d') }}" class="form-control rounded-3">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label mb-1">Observatii</label>
                                            <textarea name="observatii" class="form-control rounded-3" rows="3">{{ $bonus->observatii }}</textarea>
                                        </div>
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
            @endforeach
        @endif

        <nav>
            <ul class="pagination justify-content-center">
                {{ $bonusuri->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
