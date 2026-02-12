@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-12">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-coins me-1"></i>Bonusuri - Lunar
            </span>
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <div class="alert alert-info mb-3">
            Lista este calculata dinamic pe baza lunii selectate in campul <strong>Luna facturare</strong> din fiecare fisa caz.
            Se foloseste prima oferta acceptata si intervalul activ corespunzator lucrarii.
        </div>

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
                <div class="col-lg-3">
                    <label class="form-label mb-1">Prag minim total bonus / utilizator</label>
                    <input
                        type="number"
                        min="0"
                        step="1"
                        name="min_user_total"
                        class="form-control rounded-3"
                        value="{{ $minUserTotal > 0 ? $minUserTotal : '' }}"
                        placeholder="ex: 1000"
                    >
                </div>
            @endif
            <div class="col-lg-3">
                <button class="btn btn-primary rounded-3" type="submit">Filtreaza</button>
                <a
                    class="btn btn-success rounded-3"
                    href="{{ route('bonusuri.export', ['month' => $month, 'user_id' => $selectedUserId, 'min_user_total' => $minUserTotal]) }}"
                >
                    Export Excel
                </a>
            </div>
        </form>

        <div class="row mb-3">
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-light">
                    <div><strong>Total bonus luna:</strong> {{ number_format((int) $sumar['total_bonus'], 0, ',', '.') }} lei</div>
                    <div><strong>Pozitii bonus:</strong> {{ (int) $sumar['pozitii'] }}</div>
                    <div><strong>Fise unice:</strong> {{ (int) $sumar['fise_unice'] }}</div>
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
                        <th class="culoare2 text-white">Amputatie</th>
                        <th class="culoare2 text-white">Oferta</th>
                        <th class="culoare2 text-white">Formula</th>
                        <th class="culoare2 text-white">Bonus</th>
                        <th class="culoare2 text-white">Luna bonus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>
                                <div>{{ $row['pacient_nume'] }} {{ $row['pacient_prenume'] }}</div>
                                <div><a href="/fise-caz/{{ $row['fisa_caz_id'] }}" target="_blank">Fisa #{{ $row['fisa_caz_id'] }}</a></div>
                            </td>
                            <td>{{ $row['user_name'] }}</td>
                            <td>{{ ucfirst($row['rol']) }}</td>
                            <td>{{ $row['lucrare_denumire'] }}</td>
                            <td>{{ $row['amputatie'] }}</td>
                            <td>{{ number_format((int) $row['valoare_oferta'], 0, ',', '.') }} lei</td>
                            <td>
                                Fix: {{ (int) $row['bonus_fix'] }} |
                                %: {{ (int) $row['bonus_procent'] }}
                            </td>
                            <td><strong>{{ number_format((int) $row['bonus_total'], 0, ',', '.') }} lei</strong></td>
                            <td>{{ \Carbon\Carbon::parse($row['luna_bonus'])->format('m.Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Nu exista bonusuri eligibile in perioada selectata.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $rows->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
