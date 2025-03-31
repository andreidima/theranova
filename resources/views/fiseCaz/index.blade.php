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
                    <i class="fa-solid fa-file-medical me-1"></i>Fișe Caz ({{ $fiseCaz->total() }})
                </span>
            </div>
            <div class="col-lg-8">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center" id="datePicker">
                        <div class="col-lg-2">
                            <input type="text" class="form-control rounded-3" id="searchNume" name="searchNume" placeholder="Nume sau tel. pacient" value="{{ $searchNume }}">
                        </div>
                        <div class="col-lg-2">
                            <select name="searchTipLucrareSolicitata" class="form-select bg-white rounded-3 {{ $errors->has('searchTipLucrareSolicitata') ? 'is-invalid' : '' }}">
                                <option selected value="" style="color:white; background-color: gray;">Tip lucrare solicitată</option>
                                <option value="AK provizorie" {{ ($searchTipLucrareSolicitata == 'AK provizorie') ? 'selected' : '' }}>AK provizorie</option>
                                <option value="AK definitivă" {{ ($searchTipLucrareSolicitata == 'AK definitivă') ? 'selected' : '' }}>AK definitivă</option>
                                <option value="BK provizorie" {{ ($searchTipLucrareSolicitata == 'BK provizorie') ? 'selected' : '' }}>BK provizorie</option>
                                <option value="BK definitivă" {{ ($searchTipLucrareSolicitata == 'BK definitivă') ? 'selected' : '' }}>BK definitivă</option>
                                <option value="Disp mers" {{ ($searchTipLucrareSolicitata == 'Disp mers') ? 'selected' : '' }}>Disp mers</option>
                                <option value="Fotoliu" {{ ($searchTipLucrareSolicitata == 'Fotoliu') ? 'selected' : '' }}>Fotoliu</option>
                                <option value="Modificări" {{ ($searchTipLucrareSolicitata == 'Modificări') ? 'selected' : '' }}>Modificări</option>
                                <option value="Orteză" {{ ($searchTipLucrareSolicitata == 'Orteză') ? 'selected' : '' }}>Orteză</option>
                                <option value="PMS" {{ ($searchTipLucrareSolicitata == 'PMS') ? 'selected' : '' }}>PMS</option>
                                <option value="PPP" {{ ($searchTipLucrareSolicitata == 'PPP') ? 'selected' : '' }}>PPP</option>
                                <option value="Manșon" {{ ($searchTipLucrareSolicitata == 'Manșon') ? 'selected' : '' }}>Manșon</option>
                                <option value="Proteză sân" {{ ($searchTipLucrareSolicitata == 'Proteză sân') ? 'selected' : '' }}>Proteză sân</option>
                                <option value="Proteză sân+sutien" {{ ($searchTipLucrareSolicitata == 'Proteză sân+sutien') ? 'selected' : '' }}>Proteză sân+sutien</option>
                                <option value="Sutien" {{ ($searchTipLucrareSolicitata == 'Sutien') ? 'selected' : '' }}>Sutien</option>
                            </select>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
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
                        <div class="col-lg-4 d-flex align-items-center">
                            <label for="searchProgramareAtelier" class="pe-1">Programare atelier:</label>
                            <vue-datepicker-next
                                data-veche="{{ $searchProgramareAtelier }}"
                                nume-camp-db="searchProgramareAtelier"
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
            <div class="col-lg-2 text-end">
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
                            <th class="text-white culoare2">Pacient<br>Programare atelier</th>
                            <th class="text-white culoare2">Tip lucrare solicitată<br>Evaluare<br>Compresie manșon</th>
                            <th class="text-white culoare2 text-center">Ofertă</th>
                            <th class="text-white culoare2 text-center">Comenzi componente</th>

                            {{-- Should be removed at 01.06.2024, with everything else, including database fields --}}
                            {{-- <th class="text-white culoare2 text-center">Fișă comandă</th> --}}

                            <th class="text-white culoare2 text-center">Dată predare</th>

                            <th class="text-white culoare2 text-center">Fișă măsuri</th>

                            {{-- Pot schimba starea doar Andrei, Dana si Adrian Ples --}}
                            @if (in_array((auth()->user()->id ?? null), [1,2,72]))
                                <th class="text-white culoare2 text-center"><i class="fa-solid fa-chart-simple"></i></th>
                            @endif

                            {{-- Au fost mutate butoanele in alte parti, se poate sterge la 01.06.2024 --}}
                            {{-- <th class="text-white culoare2 text-center">Email</i></th> --}}

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
                                    {{-- {{ $fisaCaz->pacient->telefon ?? '' }} --}}
                                    {{ $fisaCaz->dateMedicale->first()->amputatie ?? '' }}
                                    {{ substr($fisaCaz->dateMedicale->first()->parte_amputata ?? '', 0, 2) }}
                                    <br>
                                    {{ $fisaCaz->pacient->judet ?? '' }}
                                    <a href="#"
                                        class="text-info" style="text-decoration: none;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_fisaCaz"
                                        title="trimite Fișa Caz prin email către utilizatori"
                                        ><span class="badge py-0 align-items-center" style="color:rgb(218, 120, 0)">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-envelope"></i><small>({{ $fisaCaz->emailuriFisaCaz->count() }})</small>
                                            </div>
                                        </span></a>
                                    <br>
                                    @if ($fisaCaz->activitati->count() > 0)
                                        <a class="" data-bs-toggle="collapse" href="#collapseFisaCaz{{ $fisaCaz->id }}Activitati" role="button" aria-expanded="false" aria-controls="collapseFisaCaz{{ $fisaCaz->id }}Activitati" style="text-decoration: none">
                                            <i class="fa-solid fa-calendar-days me-1"></i>:{{ $fisaCaz->activitati->count() }}
                                        </a>
                                        <div class="collapse" id="collapseFisaCaz{{ $fisaCaz->id }}Activitati">
                                            @foreach ($fisaCaz->activitati as $activitate)
                                                <a href="{{ $activitate->path() }}/modifica" style="text-decoration: none;">
                                                    {{ $activitate->data_inceput ? Carbon::parse($activitate->data_inceput)->isoFormat('DD.MM.YYYY HH:mm') : '' }}
                                                </a>
                                                <br>
                                            @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <i class="fa-solid fa-calendar-days me-1"></i>
                                    @endif
                                    <a href="calendar/activitati/adauga-la-fisa-caz/{{ $fisaCaz->id }}">
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    </a>
                                </td>
                                <td class="">
                                    {{ $fisaCaz->tip_lucrare_solicitata }}
                                    <br>
                                    {{ $fisaCaz->data ? Carbon::parse($fisaCaz->data)->isoFormat('DD.MM.YYYY') : '' }}
                                    <br>
                                    {{ $fisaCaz->compresie_manson ? Carbon::parse($fisaCaz->compresie_manson)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="text-end">
                                    @if ($fisaCaz->oferte->count() > 0)
                                        @foreach ($fisaCaz->oferte as $oferta)
                                            @if ($oferta->acceptata == "1")
                                                <i class="fa-solid fa-thumbs-up text-success"></i>
                                            @elseif ($oferta->acceptata == "0")
                                                <i class="fa-solid fa-thumbs-down text-danger"></i>
                                            @elseif ($oferta->acceptata == "2")
                                                <i class="fa-solid fa-exclamation-triangle text-warning"></i>
                                            @endif
                                            {{ $oferta->pret }}
                                            @if ($oferta->incasari->count() > 0)
                                                <span class="{{ $oferta->pret >= $oferta->incasari->sum('suma') ? 'text-danger' : 'text-success' }}"  title="Suma încasată">
                                                    ({{ $oferta->incasari->sum('suma') }})
                                                </span>
                                            @endif
                                            lei
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
                                    <a href="#"
                                        class="text-info" style="text-decoration: none;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_oferta"
                                        title="trimite Oferta prin email către utilizatori"
                                        ><span class="badge py-0 align-items-center" style="color:rgb(218, 120, 0)">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-envelope"></i><small>({{ $fisaCaz->emailuriOferta->count() }})</small>
                                            </div>
                                        </span></a>
                                    <a href="{{ $fisaCaz->path() }}/oferte/adauga">
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->comenzi->count() > 0)
                                        @foreach ($fisaCaz->comenzi as $comanda)
                                            @if ($comanda->sosita == "1")
                                                <i class="fa-solid fa-thumbs-up text-success"></i>
                                            @elseif ($comanda->sosita == "0")
                                                <i class="fa-solid fa-thumbs-down text-danger"></i>
                                            @endif
                                            {{ $comanda->data ? Carbon::parse($comanda->data)->isoFormat('DD.MM.YYYY') : '' }}
                                            <br>

                                            @foreach ($comanda->fisiere as $fisier)
                                                <a href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" style="text-decoration:cornflowerblue">
                                                    <span class="badge text-success" title="Deschide"><i class="fa-solid fa-file"></i></span></a>
                                            @endforeach

                                            @if ($comanda->componente->count() > 0)
                                                <a href="{{ $fisaCaz->path() }}/comenzi/{{ $comanda->id }}/export/pdf" target="_blank">
                                                    <span class="badge text-success px-1 py-0" title="PDF"><i class="fa-solid fa-file-arrow-down"></i></span></a>
                                            @endif

                                            <a href="{{ $fisaCaz->path() }}/comenzi/{{ $comanda->id }}/modifica">
                                                <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                            @if ($userCanDelete)
                                                <a href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stergeComanda{{ $comanda->id }}"
                                                    title="Șterge comanda">
                                                    <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span></a>
                                            @endif
                                            <a href="#"
                                                class="text-info" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_comanda_{{ $comanda->id }}"
                                                title="trimite Comanda prin email către utilizatori"
                                                ><span class="badge px-1 py-0 align-items-center" style="color:rgb(218, 120, 0)">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa-solid fa-envelope"></i><small>({{ $comanda->emailuriTrimise->count() }})</small>
                                                    </div>
                                                </span></a>
                                            <br>
                                        @endforeach
                                    @endif
                                    <a href="{{ $fisaCaz->path() }}/comenzi/adauga">
                                        <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                    </a>
                                </td>

                                {{-- Should be removed at 01.06.2024, with everything else, including database fields --}}
                                {{-- <td class="text-center">
                                    @if ($fisaCaz->fisa_comanda_sosita == "1")
                                        <i class="fa-solid fa-thumbs-up text-success"></i>
                                    @elseif ($fisaCaz->fisa_comanda_sosita == "0")
                                        <i class="fa-solid fa-thumbs-down text-danger"></i>
                                    @endif
                                    @if ($fisaCaz->fisa_comanda_data)
                                        {{ $fisaCaz->fisa_comanda_data ? Carbon::parse($fisaCaz->fisa_comanda_data)->isoFormat('DD.MM.YYYY') : '' }}
                                        <br>
                                    @endif

                                    @if ($fisaCaz->fisiereComanda->count() > 0)
                                        @foreach ($fisaCaz->fisiereComanda as $fisier)
                                            <a href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" title="descarcă fișier">
                                                <i class="fa-solid fa-file text-success"></i></a>
                                        @endforeach
                                        <br>
                                    @endif

                                    @if ($fisaCaz->comenziComponente->count() > 0)
                                        <a href="{{ $fisaCaz->path() }}/comenzi-componente/export/pdf" target="_blank">
                                            <span class="badge text-success px-1 py-0" title="PDF"><i class="fa-solid fa-file-arrow-down"></i></span></a>
                                    @endif

                                    @if ($fisaCaz->fisiereComanda->first() || ($fisaCaz->comenziComponente->count() > 0))
                                        <a href="{{ $fisaCaz->path() }}/comenzi-componente/toate/modifica">
                                            <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                        @if ($userCanDelete)
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeComandaComponente{{ $fisaCaz->id }}"
                                                title="Șterge comanda componente">
                                                <span class="badge text-danger px-1 py-0" title="Șterge"><i class="fa-solid fa-trash-can"></i></span></a>
                                        @endif
                                    @else
                                        <a href="{{ $fisaCaz->path() }}/comenzi-componente/toate/adauga">
                                            <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span></a>
                                    @endif
                                </td> --}}
                                <td class="text-center">
                                    {{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('DD.MM.YYYY') : '' }}
                                    <a href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#adaugaModificaProtezare{{ $fisaCaz->id }}"
                                        title="Dată predare"
                                        >
                                        @if (!$fisaCaz->protezare)
                                            <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span>
                                        @else
                                            <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if ($fisaCaz->fisiereFisaMasuri->count() > 0)
                                        @foreach ($fisaCaz->fisiereFisaMasuri as $fisier)
                                            <a href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank">
                                                <i class="fa-solid fa-file text-success"></i></a>
                                        @endforeach
                                    @endif

                                    <a href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#adaugaModificaFisaMasuriLaFisaCaz{{ $fisaCaz->id }}"
                                        title="Adaugă modifică Fișă Măsuri"
                                        >
                                        @if ($fisaCaz->fisiereFisaMasuri->count() > 0)
                                            <span class="badge text-primary px-1 py-0" title="Modifică"><i class="fa-solid fa-pen-to-square"></i></span></a>
                                        @else
                                            <span class="badge text-success" title="Adaugă"><i class="fas fa-plus-square"></i></span></a>
                                        @endif
                                </td>
                                {{-- Pot schimba starea doar Andrei, Dana si Adrian Ples --}}
                                @if (in_array((auth()->user()->id ?? null), [1,2,72]))
                                <td class="text-center">
                                    <div class="text-center">
                                        <a href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#stareDeschide{{ $fisaCaz->id }}"
                                            >
                                            <span class="badge {{ $fisaCaz->stare === 1 ? 'bg-success' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-lock-open fa-1x"></i>
                                            </span></a>
                                        <br>
                                        <a href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#stareInchide{{ $fisaCaz->id }}"
                                            >
                                            <span class="badge {{ $fisaCaz->stare === 2 ? 'bg-dark' : 'bg-white text-dark' }}">
                                                <i class="fa-solid fa-lock fa-1x"></i>
                                            </span></a>
                                        <br>
                                        <a href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#stareAnuleaza{{ $fisaCaz->id }}"
                                            >
                                            <span class="badge {{ $fisaCaz->stare === 3 ? 'bg-danger' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-ban fa-1x"></i>
                                            </span></a>
                                        {{-- <a href="{{ $fisaCaz->path() }}/stare/deschide" class="flex" title="Deschisă">
                                            <span class="badge {{ $fisaCaz->stare === 1 ? 'bg-success' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-lock-open fa-1x"></i>
                                            </span></a> --}}
                                        {{-- <br>
                                        <a href="{{ $fisaCaz->path() }}/stare/inchide" class="flex" title="Închisă">
                                            <span class="badge {{ $fisaCaz->stare === 2 ? 'bg-dark' : 'bg-white text-dark' }}">
                                                <i class="fa-solid fa-lock fa-1x"></i>
                                            </span></a>
                                        <br>
                                        <a href="{{ $fisaCaz->path() }}/stare/anuleaza" class="flex" title="Anulată">
                                            <span class="badge {{ $fisaCaz->stare === 3 ? 'bg-danger' : 'bg-light text-dark' }}">
                                                <i class="fa-solid fa-ban fa-1x"></i>
                                            </span></a> --}}
                                    </div>
                                </td>
                                @endif


                                {{-- Au fost mutate trimiterea de emailuri in alte parti, se poate sterge la 01.06.2024 --}}
                                {{-- <td class="text-center">
                                    <div style="white-space: nowrap;">
                                        <a href="#"
                                            class="text-info" style="text-decoration: none;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_fisaCaz"
                                            title="trimite Fișa Caz prin email către utilizatori"
                                            ><span class="badge bg-success text-white">FisaCaz ({{ $fisaCaz->emailuriFisaCaz->count() }})</span></a>
                                    </div>
                                    <div style="white-space: nowrap;">
                                        <a href="#"
                                            class="text-info" style="text-decoration: none;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_oferta"
                                            title="trimite Oferta prin email către utilizatori"
                                            ><span class="badge bg-primary text-white">Oferta ({{ $fisaCaz->emailuriOferta->count() }})</span></a>
                                    </div>
                                    <div style="white-space: nowrap;">
                                        <a href="#"
                                            class="text-info" style="text-decoration: none;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_comanda"
                                            title="trimite Comanda prin email către utilizatori"
                                            ><span class="badge bg-warning text-dark">Comanda ({{ $fisaCaz->emailuriComanda->count() }})</span></a>
                                    </div>
                                </td> --}}


                                <td class="">
                                    <div style="white-space: nowrap;">
                                        {{-- @if ($fisaCaz->userVanzari->email ?? null)
                                            @php
                                                $words = explode(" ", ($fisaCaz->userVanzari->name ?? ''));
                                                $acronym = $words[0] . ' ' . mb_substr($words[1], 0, 1);
                                            @endphp
                                            V(<a href="#"
                                                class="text-info" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_fisaCaz_{{ $fisaCaz->userVanzari->id }}"
                                                title="trimite Fișa Caz prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriFisaCaz->where('referinta2_id', $fisaCaz->user_vanzari)->count() }}</b></a>,<a href="#"
                                                class="text-success" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_oferta_{{ $fisaCaz->userVanzari->id }}"
                                                title="trimite Oferta prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriOferta->where('referinta2_id', $fisaCaz->user_vanzari)->count() }}</b></a>,<a href="#"
                                                class="text-primary" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_comanda_{{ $fisaCaz->userVanzari->id }}"
                                                title="trimite Fișa Comanda prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriComanda->where('referinta2_id', $fisaCaz->user_vanzari)->count() }}</b></a>):
                                                <span title="{{ $fisaCaz->userVanzari->name ?? '' }}">
                                                {{ $acronym ?? '' }}
                                            </span>
                                        @else
                                            V:
                                        @endif --}}
                                        @php
                                            $words = explode(" ", ($fisaCaz->userVanzari->name ?? ''));
                                            $acronym = ($words[0] ?? '') . ' ' . mb_substr(($words[1] ?? ''), 0, 1);
                                        @endphp
                                        V: {{ $acronym ?? '' }}
                                    </div>
                                    <div style="white-space: nowrap;">
                                        {{-- @if ($fisaCaz->userComercial->email ?? null)
                                            @php
                                                $words = explode(" ", ($fisaCaz->userComercial->name ?? ''));
                                                $acronym = $words[0] . ' ' . mb_substr($words[1], 0, 1);
                                            @endphp
                                            C(<a href="#"
                                                class="text-info" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_fisaCaz_{{ $fisaCaz->userComercial->id }}"
                                                title="trimite Fișa Caz prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriFisaCaz->where('referinta2_id', $fisaCaz->user_comercial)->count() }}</b></a>,<a href="#"
                                                class="text-success" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_oferta_{{ $fisaCaz->userComercial->id }}"
                                                title="trimite Oferta prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriOferta->where('referinta2_id', $fisaCaz->user_comercial)->count() }}</b></a>,<a href="#"
                                                class="text-primary" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_comanda_{{ $fisaCaz->userComercial->id }}"
                                                title="trimite Fișa Comanda prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriComanda->where('referinta2_id', $fisaCaz->user_comercial)->count() }}</b></a>):
                                                <span title="{{ $fisaCaz->userComercial->name ?? '' }}">
                                                {{ $acronym ?? '' }}
                                            </span>
                                        @else
                                            C:
                                        @endif --}}
                                        @php
                                            $words = explode(" ", ($fisaCaz->userComercial->name ?? ''));
                                            $acronym = ($words[0] ?? '') . ' ' . mb_substr(($words[1] ?? ''), 0, 1);
                                        @endphp
                                        C: {{ $acronym ?? '' }}
                                    </div>
                                    <div style="white-space: nowrap;">
                                        {{-- @if ($fisaCaz->userTehnic->email ?? null)
                                            @php
                                                $words = explode(" ", ($fisaCaz->userTehnic->name ?? ''));
                                                $acronym = $words[0] . ' ' . mb_substr($words[1], 0, 1);
                                            @endphp
                                            T(<a href="#"
                                                class="text-info" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_fisaCaz_{{ $fisaCaz->userTehnic->id }}"
                                                title="trimite Fișa Caz prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriFisaCaz->where('referinta2_id', $fisaCaz->user_tehnic)->count() }}</b></a>,<a href="#"
                                                class="text-success" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_oferta_{{ $fisaCaz->userTehnic->id }}"
                                                title="trimite Oferta prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriOferta->where('referinta2_id', $fisaCaz->user_tehnic)->count() }}</b></a>,<a href="#"
                                                class="text-primary" style="text-decoration: none;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_comanda_{{ $fisaCaz->userTehnic->id }}"
                                                title="trimite Fișa Comanda prin email către utilizator"
                                                ><b>{{ $fisaCaz->emailuriComanda->where('referinta2_id', $fisaCaz->user_tehnic)->count() }}</b></a>):
                                                <span title="{{ $fisaCaz->userTehnic->name ?? '' }}">
                                                {{ $acronym ?? '' }}
                                            </span>
                                        @else
                                            T:
                                        @endif --}}
                                        @php
                                            $words = explode(" ", ($fisaCaz->userTehnic->name ?? ''));
                                            $acronym = ($words[0] ?? '') . ' ' . mb_substr(($words[1] ?? ''), 0, 1);
                                        @endphp
                                        T: {{ $acronym ?? '' }}
                                    </div>
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
                                        <br>
                                        <a href="{{ $fisaCaz->path() }}/export/contract-pdf" target="_blank" class="flex">
                                            <span class="badge bg-warning text-dark">Contract</span></a>
                                        <br>
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
                        {{ $fiseCaz->links() }}
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

        {{-- Modalele pentru stergere comenzi --}}
        @foreach ($fiseCaz as $fisaCaz)
            @foreach ($fisaCaz->comenzi as $comanda)
                <div class="modal fade text-dark" id="stergeComanda{{ $comanda->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Comanda: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="text-align:left;">
                            Ești sigur că vrei să ștergi Fișa comandă din data de {{ $comanda->data ? Carbon::parse($comanda->data)->isoFormat('DD.MM.YYYY') : '' }}?
                            <br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                            <form method="POST" action="{{ $comanda->path() }}">
                                @method('DELETE')
                                @csrf
                                <button
                                    type="submit"
                                    class="btn btn-danger text-white"
                                    >
                                    Șterge comanda
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

    {{-- Modalele pentru adaugare modificare data_predare (the field name is „protezare” in the database) --}}
    @foreach ($fiseCaz as $fisaCaz)
        <div class="modal fade text-dark" id="adaugaModificaProtezare{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ $fisaCaz->path() }}/adauga-modifica-protezare">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="text-align:left;">
                            <div class="col-lg-12 mb-4">
                                <label for="data_predare" class="mb-0 ps-3">Dată predare</label>
                                <input
                                    type="text"
                                    class="form-control rounded-3 {{ $errors->has('data_predare') ? 'is-invalid' : '' }}"
                                    name="data_predare"
                                    placeholder="Eg: 20.05.2024"
                                    value="{{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('DD.MM.YYYY') : '' }}"
                                    >
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                            <button
                                type="submit"
                                class="btn btn-success text-white"
                                >
                                Salvează
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Modalele pentru adaugare modificare fisiere Fișă măsuri --}}
    @foreach ($fiseCaz as $fisaCaz)
        <div class="modal fade text-dark" id="adaugaModificaFisaMasuriLaFisaCaz{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ $fisaCaz->path() }}/adauga-modifica-fisa-masuri" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body mb-4" style="text-align:left;">
                            <label for="fisiereFisaMasuri" class="mb-0 ps-3">Fișiere fișă măsuri</label>
                            <input type="file" name="fisiereFisaMasuri[]" class="form-control rounded-3" multiple>
                            <br>
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                                <button
                                    type="submit"
                                    class="btn btn-success text-white"
                                    >
                                    Încarcă
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            @if ($fisaCaz->fisiereFisaMasuri->count() > 0)
                                    <div class="table-responsive w-100 rounded-3">
                                        <table class="table table-striped table-hover table-bordered">
                                            <tr>
                                                <th colspan="3" class="text-center">Fișe încărcate</th>
                                            </tr>
                                            @foreach ($fisaCaz->fisiereFisaMasuri as $fisier)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td style="width: ">
                                                        <a class="" href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" style="text-decoration:cornflowerblue">
                                                            {{ $fisier->nume }}</a>
                                                    </td>
                                                    <td>
                                                        {{-- <a class="btn btn-sm btn-danger" href="/fisiere/{{ $fisier->id }}/sterge">
                                                            Sterge</a> --}}
                                                        <a href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#stergeFisaCaz{{ $fisaCaz->id }}Fisier{{ $fisier->id }}"
                                                            title="Șterge fișierul"
                                                            >
                                                            <span class="badge bg-danger">Șterge</span></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

            {{-- Modalele pentru stergere fisiere de la Fisa Masuri --}}
            @foreach ($fisaCaz->fisiereFisaMasuri as $fisier)
                <div class="modal fade text-dark" id="stergeFisaCaz{{ $fisaCaz->id }}Fisier{{ $fisier->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Comanda: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="text-align:left;">
                            Ești sigur că vrei să ștergi Fișierul <b>{{ $fisier->nume }}</b>?
                            <br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                            <form method="POST" action="/fisiere/{{ $fisier->id }}/sterge">
                                @method('DELETE')
                                @csrf
                                <button
                                    type="submit"
                                    class="btn btn-danger text-white"
                                    >
                                    Șterge fișierul
                                </button>
                            </form>

                        </div>
                        </div>
                    </div>
                </div>
            @endforeach
    @endforeach


    {{-- Modalele pentru trimitere Fisa Caz prin email catre utilizator --}}
    @foreach ($fiseCaz as $fisaCaz)
        @foreach (['fisaCaz', 'oferta', 'comanda'] as $tipEmail)
            <div class="modal fade text-dark" id="trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_{{ $tipEmail }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="{{ $fisaCaz->path() }}/trimite-email-catre-utilizatori/{{ $tipEmail }}">
                        @csrf

                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title text-dark" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                                <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="text-align:left;">
                                Trimite email <b>{{ $tipEmail }}</b>.</b>
                                <br>
                                <br>
                                <label for="mesaj" class="form-label mb-0 ps-3">Mesaj</label>
                                <textarea class="form-control bg-white {{ $errors->has('mesaj') ? 'is-invalid' : '' }}"
                                    name="mesaj" rows="3">{{ old('mesaj') }}</textarea>
                                <small class="m-0 ps-3">
                                    * Completează doar dacă vrei să mai adaugi ceva la emailul standard.
                                </small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                                <button type="submit" class="btn btn-primary">Trimite email</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- Modalele pentru trimitere Comanda prin email catre utilizator --}}
    {{-- Versiune noua, cand s-a introdus posibilitatea de adaugare de comenzi multiple --}}
    @foreach ($fiseCaz as $fisaCaz)
        @foreach ($fisaCaz->comenzi as $comanda)
            <div class="modal fade text-dark" id="trimiteEmailCatreUtilizatori_{{ $fisaCaz->id }}_comanda_{{ $comanda->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="{{ $fisaCaz->path() }}/trimite-email-catre-utilizatori/comandaVersiuneNoua/{{ $comanda->id }}">
                        @csrf

                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title text-dark" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                                <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="text-align:left;">
                                Trimite email <b>comandă</b>.</b>
                                <br>
                                <br>
                                <label for="mesaj" class="form-label mb-0 ps-3">Mesaj</label>
                                <textarea class="form-control bg-white {{ $errors->has('mesaj') ? 'is-invalid' : '' }}"
                                    name="mesaj" rows="3">{{ old('mesaj') }}</textarea>
                                <small class="m-0 ps-3">
                                    * Completează doar dacă vrei să mai adaugi ceva la emailul standard.
                                </small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                                <button type="submit" class="btn btn-primary">Trimite email</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- Modalele pentru trimitere Fisa Caz prin email catre utilizator --}}
    {{-- @foreach ($fiseCaz as $fisaCaz)
        @php
            $useriIds = [];
            ($fisaCaz->userVanzari->email ?? null) ? array_push($useriIds, $fisaCaz->userVanzari->id) : '';
            ($fisaCaz->userComercial->email ?? null) ? array_push($useriIds, $fisaCaz->userComercial->id) : '';
            ($fisaCaz->userTehnic->email ?? null) ? array_push($useriIds, $fisaCaz->userTehnic->id) : '';
        @endphp
        @foreach ($useriIds as $userId)
            @foreach (['fisaCaz', 'oferta', 'comanda'] as $tipEmail)
                <div class="modal fade text-dark" id="trimiteEmailCatreUtilizator_{{ $fisaCaz->id }}_{{ $tipEmail }}_{{ $userId }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="POST" action="{{ $fisaCaz->path() }}/trimite-email-catre-utilizator/{{ $tipEmail }}/{{ $userId }}">
                            @csrf

                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="text-align:left;">
                                    Trimite email <b>{{ $tipEmail }}</b>, către <b>{{ $useri->where('id', $userId)->first()->name ?? '' }}</b>
                                    <br>
                                    <br>
                                    <label for="mesaj" class="form-label mb-0 ps-3">Mesaj</label>
                                    <textarea class="form-control bg-white {{ $errors->has('mesaj') ? 'is-invalid' : '' }}"
                                        name="mesaj" rows="3">{{ old('mesaj') }}</textarea>
                                    <small class="m-0 ps-3">
                                        * Completează doar dacă vrei să mai adaugi ceva la emailul standard.
                                    </small>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                                    <button type="submit" class="btn btn-primary">Trimite email</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endforeach --}}

    {{-- Modalele pentru schimbare stare --}}
    @foreach ($fiseCaz as $fisaCaz)
        <div class="modal fade text-dark" id="stareDeschide{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Deschide stare fișă
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                        <a href="{{ $fisaCaz->path() }}/stare/deschide" class="btn btn-success" title="Deschisă">Deschide</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-dark" id="stareInchide{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Închide stare fișă
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                        <a href="{{ $fisaCaz->path() }}/stare/inchide" class="btn btn-dark" title="Deschisă">Închide</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-dark" id="stareAnuleaza{{ $fisaCaz->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Fișă Caz: <b>{{ ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Anulează stare fișă
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                        <a href="{{ $fisaCaz->path() }}/stare/anuleaza" class="btn btn-danger" title="Deschisă">Anulează</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
