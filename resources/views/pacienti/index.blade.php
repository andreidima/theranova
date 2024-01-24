@extends ('layouts.app')

@php
    use \Carbon\Carbon;

    if (auth()->user()->hasRole("stergere")) {
        $userCanDelete = true;
    } else {
        $userCanDelete = false;
    }
@endphp

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-person-cane me-1"></i>Pacienți
                </span>
            </div>
            <div class="col-lg-6">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-4">
                            <input type="text" class="form-control rounded-3" id="searchNume" name="searchNume" placeholder="Nume" value="{{ $searchNume }}">
                        </div>
                        {{-- <div class="col-lg-4">
                            <input type="text" class="form-control rounded-3" id="searchPrenume" name="searchPrenume" placeholder="Prenume" value="{{ $searchPrenume }}">
                        </div> --}}
                        <div class="col-lg-4">
                            <input type="text" class="form-control rounded-3" id="searchTelefon" name="searchTelefon" placeholder="Telefon" value="{{ $searchTelefon }}">
                        </div>
                    </div>
                    <div class="row custom-search-form justify-content-center">
                        <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm btn-secondary text-white col-md-4 border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="{{ url()->current() }}/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă Pacient
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded-3">
                <table class="table table-striped table-hover">
                    <thead class="">
                        <tr class="" style="padding:2rem">
                            <th class="text-white culoare2">#</th>
                            <th class="text-white culoare2">Nume</th>
                            {{-- <th class="text-white culoare2">Vârsta</th> --}}
                            <th class="text-white culoare2">Telefon</th>
                            <th class="text-white culoare2">Email</th>
                            <th class="text-white culoare2">Localitatea</th>
                            <th class="text-white culoare2">Responsabil</th>
                            <th class="text-white culoare2 text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pacienti as $pacient)
                            <tr>
                                <td align="">
                                    {{ ($pacienti ->currentpage()-1) * $pacienti ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    {{ $pacient->nume }} {{ $pacient->prenume }}
                                </td>
                                {{-- <td class="">
                                    {{ $pacient->data_nastere ? Carbon::now()->diffInYears($pacient->data_nastere) : '' }}
                                </td> --}}
                                <td class="">
                                    {{ $pacient->telefon }}
                                </td>
                                <td class="">
                                    {{ $pacient->email }}
                                </td>
                                <td class="">
                                    {{ $pacient->localitate }}
                                </td>
                                <td class="">
                                    {{ $pacient->responsabil->name ?? '' }}
                                </td>
                                <td>
                                    <div class="text-end">
                                        <a href="{{ $pacient->path() }}" class="flex me-1">
                                            <span class="badge bg-success">Vizualizează</span></a>
                                        <a href="{{ $pacient->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span></a>
                                        @if ($userCanDelete)
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergePacient{{ $pacient->id }}"
                                                title="Șterge pacient"
                                                >
                                                <span class="badge bg-danger">Șterge</span></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                </table>
            </div>

                <nav>
                    <ul class="pagination justify-content-center">
                        {{ $pacienti->appends(Request::except('page'))->links() }}
                    </ul>
                </nav>
        </div>
    </div>

    @if ($userCanDelete)
        {{-- Modalele pentru stergere pacient --}}
        @foreach ($pacienti as $pacient)
            <div class="modal fade text-dark" id="stergePacient{{ $pacient->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Pacient: <b>{{ $pacient->nume }} {{ $pacient->prenume }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Ești sigur ca vrei să ștergi pacientul?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                        <form method="POST" action="{{ $pacient->path() }}">
                            @method('DELETE')
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-danger text-white"
                                >
                                Șterge Pacientul
                            </button>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection
