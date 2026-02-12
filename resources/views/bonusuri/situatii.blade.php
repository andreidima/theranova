@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-12">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-triangle-exclamation me-1"></i>Situații de rezolvat pentru bonusuri
            </span>
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <div class="alert alert-info">
            Fișe caz cu ofertă acceptată, dar fără <strong>Dată predare</strong> sau fără bifa <strong>Facturat</strong>.
            @if($canViewAll)
                Se afișează toate fișele.
            @else
                Se afișează doar fișele unde ești alocat la vânzări/tehnic.
            @endif
        </div>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Fișă</th>
                        <th class="culoare2 text-white">Pacient</th>
                        <th class="culoare2 text-white">Vânzări</th>
                        <th class="culoare2 text-white">Tehnic</th>
                        <th class="culoare2 text-white">Primă ofertă acceptată</th>
                        <th class="culoare2 text-white">Dată predare</th>
                        <th class="culoare2 text-white">Facturat</th>
                        <th class="culoare2 text-white">Acțiune</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fiseCaz as $fisaCaz)
                        @php
                            $primaOfertaAcceptata = $fisaCaz->oferte->first();
                        @endphp
                        <tr>
                            <td>#{{ $fisaCaz->id }}</td>
                            <td>{{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume ?? '' }}</td>
                            <td>{{ $fisaCaz->userVanzari->name ?? '-' }}</td>
                            <td>{{ $fisaCaz->userTehnic->name ?? '-' }}</td>
                            <td>
                                @if($primaOfertaAcceptata)
                                    {{ number_format((float) $primaOfertaAcceptata->pret, 2, ',', '.') }} lei
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $fisaCaz->protezare ? \Carbon\Carbon::parse($fisaCaz->protezare)->format('d.m.Y') : '-' }}</td>
                            <td>{{ ((int) ($fisaCaz->facturat ?? 0) === 1) ? 'Da' : 'Nu' }}</td>
                            <td>
                                <a href="/fise-caz/{{ $fisaCaz->id }}" class="btn btn-sm btn-primary" target="_blank">Deschide fișa</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Nu există situații restante.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $fiseCaz->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection

