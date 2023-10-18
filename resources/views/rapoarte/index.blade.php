@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fas fa-bars me-1"></i>Rapoarte
                </span>
            </div>
            <div class="col-lg-7">
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <form class="needs-validation" novalidate method="GET" action="{{ url()->current()  }}">
                        @csrf
                        <div class="row mb-1 custom-search-form justify-content-center">
                            <div class="col-lg-12 mb-5 align-items-center text-center" id="datePicker">
                                <label for="interval" class="mb-1 py-1 px-3 culoare2 rounded-3">Alege intervalul</label>
                                <vue-datepicker-next
                                    data-veche="{{ $interval ?? (\Carbon\Carbon::today()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d') . "," . \Carbon\Carbon::today()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d')) }}"
                                    nume-camp-db="interval"
                                    tip="date"
                                    range="range"
                                    value-type="YYYY-MM-DD"
                                    format="DD.MM.YYYY"
                                    :latime="{ width: '210px' }"
                                ></vue-datepicker-next>
                            </div>
                            <span class="py-1 px-3 rounded-3 culoare2 text-center">Alege raportul dorit</span>
                            <div class="row">
                                @if (!(auth()->user()->name == "Contabilitate"))
                                    <div class="col-lg-6">
                                        <div class="list-group p-0 list-group-numbered rounded-3">
                                            <button type="submit" name="action" value="recoltariSangeCtsvToate" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport Director
                                            </button>
                                            <button type="submit" name="action" value="recoltariSangeCtsvToateDetaliatPeZile" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport recoltări detaliat pe zile
                                            </button>
                                            <button type="submit" name="action" value="intrariDetaliatePeZile" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport intrări detaliat pe zile
                                            </button>
                                            <button type="submit" name="action" value="livrariDetaliatePeZile" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport livrări (comenzi) detaliat pe zile
                                            </button>
                                            <button type="submit" name="action" value="rebuturiDetaliatePeZile" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport rebuturi detaliat pe zile
                                            </button>
                                            <button type="submit" name="action" value="rebuturiDetaliatePeZileGrupatePerProdus" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport rebuturi detaliat pe zile - grupate per produs
                                            </button>
                                            <button type="submit" name="action" value="recoltariNevalidate" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport recoltări nevalidate
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="list-group p-0 list-group-numbered rounded-3">
                                            <button type="submit" name="action" value="stocuriPungiSange" class="list-group-item list-group-item-action" aria-current="true">
                                                Stocuri pungi sânge (se va descărca stocul până la prima dată aleasă din interval)
                                            </button>
                                            <button type="submit" name="action" value="situatiaSangeluiSiAProduselorDinSange" class="list-group-item list-group-item-action" aria-current="true">
                                                Situația sângelui și a produselor din sânge (CONTABILITATE)
                                            </button>
                                            <button type="submit" name="action" value="DProcesare" class="list-group-item list-group-item-action" aria-current="true">
                                                D. Procesare
                                            </button>
                                            <button type="submit" name="action" value="G1Rebut" class="list-group-item list-group-item-action" aria-current="true">
                                                G.1. Rebut
                                            </button>
                                            <button type="submit" name="action" value="G2RebutRepartitie" class="list-group-item list-group-item-action" aria-current="true">
                                                G.2. Rebut repartiție
                                            </button>
                                            <button type="submit" name="action" value="HUnitatiValidateDonareStandard" class="list-group-item list-group-item-action" aria-current="true">
                                                H. Număr unități validate donare standard (ST și CS validate/ eliberate din carantină*) + afereză
                                            </button>
                                            <button type="submit" name="action" value="JCerereSiDistributie" class="list-group-item list-group-item-action" aria-current="true">
                                                J. Cerere și Distribuție
                                            </button>
                                            <button type="submit" name="action" value="MIncidenteDeaLungulActivitatiiDinCts" class="list-group-item list-group-item-action" aria-current="true">
                                                M. Incidente (număr) de-a lungul activității din CTS
                                            </button>
                                            <button type="submit" name="action" value="livrariPerProdusPerGrupa" class="list-group-item list-group-item-action" aria-current="true">
                                                Raport livrări grupate după CTS, produs și grupa sanguină
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-6 mx-auto">
                                        <div class="list-group p-0 list-group-numbered rounded-3">
                                            <button type="submit" name="action" value="situatiaSangeluiSiAProduselorDinSange" class="list-group-item list-group-item-action" aria-current="true">
                                                Situația sângelui și a produselor din sânge (CONTABILITATE)
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- @foreach ($recoltariSange->sortBy('data') as $recoltare)
                {{ $recoltare->data }}
                <br>
            @endforeach
            {{ $recoltariSange->count() }} --}}

        </div>
    </div>

@endsection
