@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
@if (!str_contains(url()->current(), 'mod-afisare' ))
    <div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-calendar-day me-1"></i>Activități
                </span>
            </div>
            <div class="col-lg-6">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-8">
                            <input type="text" class="form-control rounded-3" id="searchDescriere" name="searchDescriere" placeholder="Descriere" value="{{ $searchDescriere }}">
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
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă activitate
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded-3">
                <table class="table table-striped table-hover">
                    <thead class="">
                        <tr class="" style="padding:2rem">
                            <th class="culoare2 text-white">#</th>
                            <th class="culoare2 text-white">Calendar</th>
                            <th class="culoare2 text-white">Descriere</th>
                            <th class="culoare2 text-white">Data</th>
                            <th class="culoare2 text-white text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activitati as $activitate)
                            <tr>
                                <td align="">
                                    {{ ($activitati ->currentpage()-1) * $activitati ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    {{ $activitate->calendar->nume ?? '' }}
                                </td>
                                <td class="">
                                    {{ $activitate->descriere }}
                                </td>
                                <td class="">
                                    {{ $activitate->data_inceput ? Carbon::parse($activitate->data_inceput)->isoFormat('DD.MM.YYYY HH:mm') : '' }}
                                    {{ $activitate->data_sfarsit ? ' - ' . Carbon::parse($activitate->data_sfarsit)->isoFormat('DD.MM.YYYY HH:mm') : ''}}
                                </td>
                                <td class="">
                                    <div class="text-end">
                                        <a href="{{ $activitate->path() }}" class="flex me-1">
                                            <span class="badge bg-success">Vizualizează</span></a>
                                        <a href="{{ $activitate->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span></a>
                                        {{-- @if ($activitateCanDelete) --}}
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeActivitate{{ $activitate->id }}"
                                                title="Șterge activitate"
                                                >
                                                <span class="badge bg-danger">Șterge</span></a>
                                        {{-- @endif --}}
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
                        {{$activitati->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- @if ($activitateCanDelete) --}}
        {{-- Modalele pentru stergere activitate --}}
        @foreach ($activitati as $activitate)
            <div class="modal fade text-dark" id="stergeActivitate{{ $activitate->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Activitate: <b>{{ $activitate->descriere }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Ești sigur ca vrei să ștergi activitatea?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                        <form method="POST" action="{{ $activitate->path() }}">
                            @method('DELETE')
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-danger text-white"
                                >
                                Șterge activitatea
                            </button>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    {{-- @endif --}}
@elseif (str_contains(url()->current(), 'mod-afisare-lunar' ))
    <div class="mx-3 px-0 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-calendar-day me-1"></i>Activități
                </span>
            </div>
            <div class="col-lg-6">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-8">
                            {{-- <input type="text" class="form-control rounded-3" id="searchDescriere" name="searchDescriere" placeholder="Descriere" value="{{ $searchDescriere }}"> --}}
                            <div class="d-flex justify-content-center">
                                @foreach ($calendare as $calendar)
                                    <div class="me-4 px-2 rounded-3" style="color:{{ $calendar->culoare }}; white-space: nowrap;">
                                        <input class="form-check-input border border-1 border-dark" type="checkbox" name="searchCalendareSelectate[]" value="{{ $calendar->id }}" id="Calendar{{ $calendar->id }}"
                                            {{ in_array($calendar->id, $searchCalendareSelectate) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="Calendar{{ $calendar->id }}">
                                            {{ $calendar->nume }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row custom-search-form justify-content-center mb-3">
                        <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm btn-secondary text-white col-md-4 border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>

                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-8 text-center">
                            <input type="hidden" id="searchLunaCalendar" name="searchLunaCalendar" value="{{ $searchLunaCalendar }}">
                            <button class="btn btn-sm btn-primary text-white border border-light rounded-3" type="submit" name="action" value="previousMonth">
                                <i class="fa-solid fa-angles-left"></i>
                            </button>
                            {{ ucfirst($searchLunaCalendar->isoFormat('MMMM YYYY')) }}
                            <button class="btn btn-sm btn-primary text-white border border-light rounded-3" type="submit" name="action" value="nextMonth">
                                <i class="fa-solid fa-angles-right"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="/calendar/activitati/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă activitate
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-0">

            @include ('errors')

            @isset($coduriApartamente)
                <div class="px-3 pt-3">
                    <div class="d-flex flex-wrap justify-content-center gap-3 fw-semibold">
                        @forelse ($coduriApartamente as $codApartament)
                            <span class="badge bg-light text-dark border border-dark rounded-3 px-3 py-2">
                                {{ $codApartament['eticheta'] }}:
                                <span class="text-primary">{{ $codApartament['valoare'] ?? '—' }}</span>
                            </span>
                        @empty
                            <span class="badge bg-light text-dark border border-dark rounded-3 px-3 py-2">
                                Nu există coduri pentru apartamentele din Oradea.
                            </span>
                        @endforelse
                    </div>
                </div>
            @endisset

            <style>
            #lunar {
            border-collapse: collapse;
            color: rgb(151, 0, 0);
            margin: auto;
            }

            #lunar th, #lunar td {
                border: 1px solid rgb(183, 183, 183);
            }

            #lunar th {
            text-align: center;
            padding-top: 12px;
            padding-bottom: 12px;
            }

            #lunar td {
            padding: 2px 2px;
            text-align: left;
            vertical-align: text-top;

            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            /* white-space: nowrap; */
            }

            #line {
            height: 15px;
            width: 11px;
            /* background-color: rgb(255, 118, 118); */
            /* border-radius: 50%; */
            display: inline-block;
            }

            #dot {
            height: 11px;
            width: 11px;
            /* background-color: rgb(255, 118, 118); */
            border-radius: 50%;
            display: inline-block;
            }
            </style>

            <div class="row p-md-4 rounded-3 justify-content-center">
                <div class="table-responsive rounded-3 px-0" style="background-color: rgb(255, 255, 255)">
                    <table class="table align-middle" id="lunar" style="width: 100%">
                        {{-- To delete 01.06.2024 - this search was moved on the one from the top --}}
                        {{-- <tr>
                            <th colspan="7" class="culoare2 text-white">
                                <h5 class="mb-0 d-flex justify-content-center align-items-center">

                                    <form class="needs-validation mx-2" novalidate method="GET" action="{{ url()->current() }}">
                                        @csrf
                                            <input type="hidden" id="searchLunaCalendar" name="searchLunaCalendar" value="{{ Carbon::parse($searchLunaCalendar)->subMonthNoOverflow() }}">
                                            <button class="btn btn-sm btn-primary text-white border border-light rounded-3" type="submit">
                                                <i class="fa-solid fa-angles-left"></i>
                                            </button>
                                    </form>
                                    {{ ucfirst($searchLunaCalendar->isoFormat('MMMM YYYY')) }}
                                    <form class="needs-validation mx-2" novalidate method="GET" action="{{ url()->current() }}">
                                        @csrf
                                            <input type="hidden" id="searchLunaCalendar" name="searchLunaCalendar" value="{{ Carbon::parse($searchLunaCalendar)->addMonthNoOverflow() }}">
                                            <button class="btn btn-sm btn-primary text-white border border-light rounded-3" type="submit">
                                                <i class="fa-solid fa-angles-right"></i>
                                            </button>
                                    </form>
                                </h5>
                            </th>
                        </tr> --}}
                        <tr>
                            <th class="culoare2 text-white" width="13%">
                                Luni
                            </th>
                            <th class="culoare2 text-white" width="13%">
                                Marți
                            </th>
                            <th class="culoare2 text-white" width="13%">
                                Miercuri
                            </th>
                            <th class="culoare2 text-white" width="13%">
                                Joi
                            </th>
                            <th class="culoare2 text-white" width="13%">
                                Vineri
                            </th>
                            <th class="culoare2 text-white" width="13%">
                                Sâmbătă
                            </th>
                            <th class="culoare2 text-white" width="13%">
                                Duminică
                            </th>
                        </tr>

                        @php
                            $ziua = Carbon::parse($searchLunaCalendar)->startOfMonth()->startOfWeek();

                            $azi = Carbon::today();
                            $primaZiDinLuna = Carbon::parse($searchLunaCalendar)->startOfMonth();
                            $ultimaZiDinLuna = Carbon::parse($searchLunaCalendar)->endOfMonth();

                            $ultimaZiDinCalendar = Carbon::parse($searchLunaCalendar)->endOfMonth()->endOfWeek();
                        @endphp
                        @while ($ziua <= $ultimaZiDinCalendar)
                            @if (($ziua->isMonday() == true))
                                <tr>
                            @endif

                            <td class="" style="">
                                <p class="m-0" style="text-align: right;">
                                    {{-- @if ($ziua == $azi)
                                        <span class="px-1 rounded-3 border border-dark border-1" style="background-color:rgb(179, 209, 255); color: rgb(0, 0, 0); font-size:80%; font-weight:bold"> --}}
                                    @if (($ziua < $primaZiDinLuna) || ($ziua > $ultimaZiDinLuna))
                                        {{-- <span class="px-1 rounded-3" style="color: grey; font-size:80%"> --}}
                                        <span class="px-1 rounded-3 text-secondary text-center mx-2">
                                    @else
                                        {{-- <span class="px-1 rounded-3" style="background-color:rgb(74, 96, 149); color: rgb(255, 255, 255); font-size:80%"> --}}
                                        <span class="culoare1 text-white px-1 rounded-3 text-center mx-2" style="display: inline-block; width: 30px">
                                    @endif
                                        {{ $ziua->day }}
                                    </span>
                                </p>

                                {{-- @foreach ($activitatiPeMaiMulteZile as $activitate) --}}
                                @foreach ($activitatiPeMaiMulteZile->where('data_inceput', '<', $ziua->addDay()->todatestring())->where('data_sfarsit', '>=', $ziua->subDay()->todatestring()) as $activitate)
                                    <div style="background-color:bisque">
                                        <span id="line" style="background-color:{{ $activitate->calendar->culoare }}"></span>
                                        {{-- <span class="px-1 rounded-3" style="color:grey">
                                            {{ Carbon::parse($activitate->data_inceput)->isoFormat('HH:mm') }}</span>- --}}
                                        <a href="/calendar/activitati/{{ $activitate->id }}/modifica" style="text-decoration: none; color:{{ $activitate->calendar->culoare }};">
                                            @switch ($activitate->cazare)
                                                @case("Apartament 1")
                                                    Ap 1 -
                                                    @break
                                                @case("Apartament 2")
                                                    Ap 2 -
                                                    @break
                                                @case("Apartament 3")
                                                    Ap 3 -
                                                    @break
                                            @endswitch
                                            {{ $activitate->descriere }}
                                            {{-- {{ $activitate->cazare ? '(' . $activitate->cazare . ')' : '' }} --}}
                                        </a>
                                    </div>
                                @endforeach

                                @foreach ($activitatiPeOZi->where('data_inceput', '>=', $ziua->todatestring())->where('data_inceput', '<', $ziua->addDay()->todatestring()) as $activitate)
                                    <span id="dot" style="background-color:{{ $activitate->calendar->culoare }}"></span>
                                    {{-- <span class="px-0 rounded-3" style="color:grey">
                                        {{ Carbon::parse($activitate->data_inceput)->isoFormat('HH:mm') }}</span> - --}}
                                    <a href="/calendar/activitati/{{ $activitate->id }}/modifica" style="text-decoration: none; color:{{ $activitate->calendar->culoare }};">
                                        {{ Carbon::parse($activitate->data_inceput)->isoFormat('HH:mm') }} -
                                        @switch ($activitate->cazare)
                                            @case("Apartament 1")
                                                Ap 1 -
                                                @break
                                            @case("Apartament 2")
                                                Ap 2 -
                                                @break
                                            @case("Apartament 3")
                                                Ap 3 -
                                                @break
                                        @endswitch
                                        {{ $activitate->descriere }}</a>
                                    <br>
                                @endforeach
                            </td>

                            @if ($ziua->isMonday() == true)
                                </tr>
                            @endif
                        @endwhile

                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
