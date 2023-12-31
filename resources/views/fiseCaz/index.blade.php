@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-file-medical me-1"></i>Fișe Caz
                </span>
            </div>
            <div class="col-lg-7">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current()  }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-3">
                            <input type="text" class="form-control rounded-3" id="searchNume" name="searchNume" placeholder="Nume pacient" value="{{ $searchNume }}">
                        </div>
                        <div class="col-lg-3">
                            <select name="searchUserVanzari" class="form-select bg-white rounded-3 {{ $errors->has('searchUserVanzari') ? 'is-invalid' : '' }}">
                                <option selected value="" style="color:white; background-color: gray;">Vânzări</option>
                                @foreach ($useri->where('role', 1) as $user)
                                    <option value="{{ $user->id }}" {{ ($user->id === intval($searchUserVanzari)) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="searchUserComercial" class="form-select bg-white rounded-3 {{ $errors->has('searchUserComercial') ? 'is-invalid' : '' }}">
                                <option selected value="" style="color:white; background-color: gray;">Comercial</option>
                                @foreach ($useri->where('role', 2) as $user)
                                    <option value="{{ $user->id }}" {{ ($user->id === intval($searchUserComercial)) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="searchUserTehnic" class="form-select bg-white rounded-3 {{ $errors->has('searchUserTehnic') ? 'is-invalid' : '' }}">
                                <option selected value="" style="color:white; background-color: gray;">Tehnic</option>
                                @foreach ($useri->where('role', 3) as $user)
                                    <option value="{{ $user->id }}" {{ ($user->id === intval($searchUserTehnic)) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
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
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă Fișă Caz
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
                            <th class="text-white culoare2">Pacient</th>
                            <th class="text-white culoare2 text-center">Evaluare</th>
                            <th class="text-white culoare2 text-center">Ofertă</th>
                            <th class="text-white culoare2 text-center">Planificare mulaj</th>
                            <th class="text-white culoare2 text-center">Comandă</th>
                            <th class="text-white culoare2 text-center">Protezare</th>
                            <th class="text-white culoare2 text-center">Documente</th>
                            <th class="text-white culoare2 text-center">Stare</th>
                            <th class="text-white culoare2">Utilizator</th>
                            <th class="text-white culoare2 text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fiseCaz as $fisaCaz)
                            <tr>
                                <td align="">
                                    {{ ($fiseCaz ->currentpage()-1) * $fiseCaz ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    <a href="{{ $fisaCaz->pacient->path() }}">
                                        {{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume ?? ''}}
                                    </a>
                                    <br>
                                    {{ $fisaCaz->pacient->telefon ?? '' }}
                                    <br>
                                    {{ $fisaCaz->pacient->localitate ?? '' }}
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->data)
                                        {{ $fisaCaz->data ? Carbon::parse($fisaCaz->data)->isoFormat('DD.MM.YYYY') : '' }}
                                        <br>
                                        <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span>
                                    @else
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->oferta)
                                        {{ $fisaCaz->oferta ? Carbon::parse($fisaCaz->oferta)->isoFormat('DD.MM.YYYY') : '' }}
                                        <br>
                                        <span class="badge text-success" title="Descarcă"><i class="fa-solid fa-file-arrow-down"></i></span>
                                        <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span>
                                    @else
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->planificare_mulaj)
                                        {{ $fisaCaz->planificare_mulaj ? Carbon::parse($fisaCaz->planificare_mulaj)->isoFormat('DD.MM.YYYY') : '' }}
                                        <br>
                                        <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span>
                                    @else
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->comanda)
                                        {{ $fisaCaz->comanda ? Carbon::parse($fisaCaz->comanda)->isoFormat('DD.MM.YYYY') : '' }}
                                        <br>
                                        <span class="badge text-success" title="Descarcă"><i class="fa-solid fa-file-arrow-down"></i></span>
                                        <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span>
                                    @else
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->protezare)
                                        {{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('DD.MM.YYYY') : '' }}
                                        <br>
                                        <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span>
                                    @else
                                        <span class="badge text-success"><i class="fas fa-plus-square"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge bg-warning text-dark">Ofertă</span>
                                        <span class="badge bg-success">Contract</span>
                                        <span class="badge bg-primary">Comandă</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <a href="{{ $fisaCaz->path() }}/stare/deschide" class="flex me-1" title="Deschisă">
                                            <span class="badge {{ $fisaCaz->stare === 1 ? 'bg-success' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-lock-open fa-1x"></i>
                                            </span></a>
                                        <a href="{{ $fisaCaz->path() }}/stare/inchide" class="flex me-1" title="Închisă">
                                            <span class="badge {{ $fisaCaz->stare === 2 ? 'bg-dark' : 'bg-white text-dark' }}">
                                                <i class="fa-solid fa-lock fa-1x"></i>
                                            </span></a>
                                        <a href="{{ $fisaCaz->path() }}/stare/anuleaza" class="flex me-1" title="Anulată">
                                            <span class="badge {{ $fisaCaz->stare === 3 ? 'bg-danger' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-ban fa-1x"></i>
                                            </span></a>
                                    </div>
                                </td>
                                <td class="">
                                    V: {{ $fisaCaz->userVanzari->name ?? '' }}
                                    <br>
                                    C: {{ $fisaCaz->userComercial->name ?? '' }}
                                    <br>
                                    T: {{ $fisaCaz->userTehnic->name ?? '' }}
                                </td>
                                <td>
                                    <div class="text-end">
                                        <a href="{{ $fisaCaz->path() }}" class="flex me-1">
                                            <span class="badge bg-success">Vizualizează</span></a>
                                        <a href="{{ $fisaCaz->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span></a>
                                        <a href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#stergeFisaCaz{{ $fisaCaz->id }}"
                                            title="Șterge fișă caz"
                                            >
                                            <span class="badge bg-danger">Șterge</span></a>
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
                        {{$fiseCaz->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere fisa caz --}}
    @foreach ($fiseCaz as $fisaCaz)
        <div class="modal fade text-dark" id="stergeFisaCaz{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align:left;">
                    Ești sigur că vrei să ștergi Fișa Caz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                    <form method="POST" action="{{ $fisaCaz->path() }}">
                        @method('DELETE')
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger text-white"
                            >
                            Șterge Fișa Caz
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
