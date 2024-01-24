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
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-file-medical me-1"></i>Fișe Caz
                </span>
            </div>
            <div class="col-lg-7">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-6">
                            <input type="text" class="form-control rounded-3" id="searchNume" name="searchNume" placeholder="Nume sau tel. pacient" value="{{ $searchNume }}">
                        </div>
                        <div class="col-lg-6 d-flex align-items-center" id="datePicker">
                            <label for="searchInterval" class="pe-1">Interval protezare:</label>
                            <vue-datepicker-next
                                data-veche="{{ $searchInterval }}"
                                nume-camp-db="searchInterval"
                                tip="date"
                                range="range"
                                value-type="YYYY-MM-DD"
                                format="DD.MM.YYYY"
                                :latime="{ width: '210px' }"
                            ></vue-datepicker-next>
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
                            <th class="text-white culoare2">Tip proteză</th>
                            <th class="text-white culoare2 text-center">Evaluare</th>
                            <th class="text-white culoare2 text-center">Ofertă</th>
                            <th class="text-white culoare2 text-center">Fișă comandă</th>
                            <th class="text-white culoare2 text-center">Compresie manșon</th>
                            <th class="text-white culoare2 text-center">Protezare</th>
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
                                    <a href="{{ $fisaCaz->pacient ? $fisaCaz->pacient->path() : '' }}">
                                        {{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume ?? ''}}
                                    </a>
                                    <br>
                                    {{ $fisaCaz->pacient->telefon ?? '' }}
                                    <br>
                                    {{ $fisaCaz->pacient->judet ?? '' }}
                                </td>
                                <td class="">
                                    {{ $fisaCaz->dateMedicale->first()->tip_proteza ?? '' }}
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->data)
                                        {{ $fisaCaz->data ? Carbon::parse($fisaCaz->data)->isoFormat('DD.MM.YYYY') : '' }}
                                        {{-- <br>
                                        <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span> --}}
                                    @else
                                        {{-- <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span> --}}
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($fisaCaz->oferte->count() > 0)
                                        @foreach ($fisaCaz->oferte as $oferta)
                                            @if ($oferta->acceptata == "1")
                                                <i class="fa-solid fa-thumbs-up text-success"></i>
                                            @elseif ($oferta->acceptata == "0")
                                                <i class="fa-solid fa-thumbs-down text-danger"></i>
                                            @endif
                                            {{ $oferta->pret }} lei
                                            @foreach ($oferta->fisiere as $fisier)
                                                <a href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" style="text-decoration:cornflowerblue">
                                                    <span class="badge text-success" title="Deschide"><i class="fa-solid fa-file-arrow-down"></i></span></a>
                                            @endforeach
                                            <a href="{{ $oferta->path() }}/modifica">
                                                <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                            @if ($userCanDelete)
                                                <a href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stergeOferta{{ $oferta->id }}"
                                                    title="Șterge oferta">
                                                    <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span></a>
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                    <a href="{{ $fisaCaz->path() }}/oferte/adauga">
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->comenziComponente->count() > 0)
                                        @if ($fisaCaz->fisa_comanda_sosita == "1")
                                            <i class="fa-solid fa-thumbs-up text-success"></i>
                                        @elseif ($fisaCaz->fisa_comanda_sosita == "0")
                                            <i class="fa-solid fa-thumbs-down text-danger"></i>
                                        @endif
                                        {{ \Carbon\Carbon::parse($fisaCaz->comenziComponente->first()->created_at)->isoFormat('DD.MM.YYYY') }}
                                        <br>
                                        <a href="{{ $fisaCaz->path() }}/comenzi-componente/export/pdf" target="_blank">
                                            <span class="badge text-success px-1 py-0" title="PDF"><i class="fa-solid fa-file-arrow-down"></i></span></a>
                                        <a href="{{ $fisaCaz->path() }}/comenzi-componente/toate/modifica">
                                            <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                        @if ($userCanDelete)
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeComandaComponente{{ $fisaCaz->id }}"
                                                title="Șterge comanda componente">
                                                <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span></a>
                                        @endif
                                        <br>
                                    @else
                                        <a href="{{ $fisaCaz->path() }}/comenzi-componente/toate/adauga">
                                            <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $fisaCaz->compresie_manson ? Carbon::parse($fisaCaz->compresie_manson)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="text-center">
                                    {{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td>
                                    <div class="text-center">
                                        <a href="{{ $fisaCaz->path() }}/stare/deschide" class="flex me-1" title="Deschisă">
                                            <span class="badge {{ $fisaCaz->stare === 1 ? 'bg-success' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-lock-open fa-1x"></i>
                                            </span></a>
                                        <br>
                                        <a href="{{ $fisaCaz->path() }}/stare/inchide" class="flex me-1" title="Închisă">
                                            <span class="badge {{ $fisaCaz->stare === 2 ? 'bg-dark' : 'bg-white text-dark' }}">
                                                <i class="fa-solid fa-lock fa-1x"></i>
                                            </span></a>
                                        <br>
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
                                        <a href="{{ $fisaCaz->path() }}" class="flex">
                                            <span class="badge bg-success">Vizualizează</span></a>
                                        <br>
                                        <a href="{{ $fisaCaz->path() }}/modifica" class="flex">
                                            <span class="badge bg-primary">Modifică</span></a>
                                        <br>
                                        @if ($userCanDelete)
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeFisaCaz{{ $fisaCaz->id }}"
                                                title="Șterge fișă caz"
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
                        {{$fiseCaz->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    @if ($userCanDelete)
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

        {{-- Modalele pentru stergere oferte --}}
        @foreach ($fiseCaz as $fisaCaz)
            @foreach ($fisaCaz->oferte as $oferta)
                <div class="modal fade text-dark" id="stergeOferta{{ $oferta->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Ofertă: <b>{{ ($oferta->fisaCaz->pacient->nume ?? '') . ' ' . ($oferta->fisaCaz->pacient->prenume ?? '') }}</b></h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="text-align:left;">
                            Obiectul contractului: {{ $oferta->obiect_contract }}
                            <br>
                            Pret: {{ $oferta->pret }} lei
                            <br><br>
                            <p class="m-0 text-center">
                                Ești sigur că vrei să ștergi Oferta?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                            <form method="POST" action="{{ $oferta->path() }}">
                                @method('DELETE')
                                @csrf
                                <button
                                    type="submit"
                                    class="btn btn-danger text-white"
                                    >
                                    Șterge oferta
                                </button>
                            </form>

                        </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        {{-- Modalele pentru stergere comenziComponente --}}
        @foreach ($fiseCaz as $fisaCaz)
            <div class="modal fade text-dark" id="stergeComandaComponente{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Pacient: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Ești sigur că vrei să ștergi Fișa comandă?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                        <form method="POST" action="{{ $fisaCaz->path() }}/comenzi-componente/toate/sterge">
                            @method('DELETE')
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-danger text-white"
                                >
                                Șterge Fișa comandă
                            </button>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection
