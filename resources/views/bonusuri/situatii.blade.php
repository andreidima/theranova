@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-12">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-triangle-exclamation me-1"></i>Situatii de rezolvat pentru bonusuri
            </span>
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <div class="alert alert-info">
            Fise caz cu oferta acceptata, dar fara <strong>Data predare</strong> sau nefacturata (<strong>Luna facturare</strong>).
            @if($canViewAll)
                Se afiseaza toate fisele.
            @else
                Se afiseaza doar fisele unde esti alocat la vanzari/tehnic.
            @endif
        </div>

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Fisa</th>
                        <th class="culoare2 text-white">Pacient</th>
                        <th class="culoare2 text-white">Vanzari</th>
                        <th class="culoare2 text-white">Tehnic</th>
                        <th class="culoare2 text-white">Prima oferta acceptata</th>
                        <th class="culoare2 text-white">Data predare</th>
                        <th class="culoare2 text-white">Actiune</th>
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
                                    {{ number_format((float) $primaOfertaAcceptata->pret, 0, ',', '.') }} lei
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $fisaCaz->protezare ? \Carbon\Carbon::parse($fisaCaz->protezare)->format('d.m.Y') : '-' }}</td>
                            <td>
                                <a href="/fise-caz?searchFisaId={{ $fisaCaz->id }}" class="btn btn-sm btn-primary" target="_blank">Deschide fisa</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nu exista situatii restante.</td>
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
