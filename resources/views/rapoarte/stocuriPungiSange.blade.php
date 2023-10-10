@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fas fa-bars me-1"></i>Rapoarte - stocuri pungi sânge
                </span>
            </div>
            <div class="col-lg-7">
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="row">
                <div class="col-lg-12 mx-auto text-center">
                    <h3 style="margin: 0">Stocuri pungi sânge</h3>
                    Până la data: {{ \Carbon\Carbon::parse(strtok($interval, ','))->isoFormat('DD.MM.YYYY') }}

                    <br>
                    <br>
                    @foreach ($recoltariSange->sortby('produs.nume')->groupBy('recoltari_sange_produs_id') as $recoltariSangeGrupateDupaProdus)
                        <form class="needs-validation" novalidate method="GET" action="/rapoarte/stocuri-pungi-sange">
                            @csrf
                            <input type="hidden" name="interval" value="{{ $interval }}">
                            <input type="hidden" name="produsId" value="{{ $recoltariSangeGrupateDupaProdus->first()->produs->id }}">

                            {{-- Daca sunt mai mult de 1000 de pungi, se sparg recoltarile in mai multe fisiere pe fiecare grupa in parte --}}
                            @if($recoltariSangeGrupateDupaProdus->count() > 1000)
                                {{-- <span class="px-2 rounded-3" style="font-weight:bold; background-color: rgb(195, 235, 254)"> --}}
                                    {{ $recoltariSangeGrupateDupaProdus->first()->produs->nume }}:
                                    {{ $recoltariSangeGrupateDupaProdus->count() }} pungi /
                                    {{ number_format((float)($recoltariSangeGrupateDupaProdus->sum('cantitate') / 1000), 2, '.', '') }} litri
                                {{-- </span> --}}
                                    <br>
                                @foreach ($recoltariSangeGrupateDupaProdus->sortby('grupa.id')->groupBy('recoltari_sange_grupa_id') as $recoltariSangeGrupateDupaProdusGrupateDupaGrupa)
                                <button class="btn btn-primary mb-1" type="submit" name="action" value="{{ $recoltariSangeGrupateDupaProdusGrupateDupaGrupa->first()->grupa->id }}">
                                    {{-- {{ $recoltariSangeGrupateDupaProdusGrupateDupaGrupa->first()->produs->nume }} --}}
                                    {{ $recoltariSangeGrupateDupaProdusGrupateDupaGrupa->first()->grupa->nume }}:
                                    {{ $recoltariSangeGrupateDupaProdusGrupateDupaGrupa->count() }} pungi /
                                    {{ number_format((float)($recoltariSangeGrupateDupaProdusGrupateDupaGrupa->sum('cantitate') / 1000), 2, '.', '') }} litri
                                </button>
                                @endforeach
                            @else
                                <button class="btn btn-primary" type="submit">
                                    {{ $recoltariSangeGrupateDupaProdus->first()->produs->nume }}:
                                    {{ $recoltariSangeGrupateDupaProdus->count() }} pungi /
                                    {{ number_format((float)($recoltariSangeGrupateDupaProdus->sum('cantitate') / 1000), 2, '.', '') }} litri
                                </button>
                            @endif
                        </form>
                        <br>
                    @endforeach
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
