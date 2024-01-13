@php
    use \Carbon\Carbon;
@endphp

@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="culoare2 border border-secondary p-2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-file-medical me-1"></i>Fișe caz / {{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume }}
                    </span>
                </div>

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >

            @include ('errors')

                    <div class="table-responsive col-md-12 mx-auto">
                        <table class="table table-striped table-hover"
                        >
                            <tr>
                                <td class="pe-4">
                                    Evaluare:
                                </td>
                                <td>
                                    {{ $fisaCaz->data ? Carbon::parse($fisaCaz->data)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Compresie manșon:
                                </td>
                                <td>
                                    {{ $fisaCaz->compresie_manson ? Carbon::parse($fisaCaz->compresie_manson)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Protezare:
                                </td>
                                <td>
                                    {{ $fisaCaz->protezare ? Carbon::parse($fisaCaz->protezare)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Vânzări
                                </td>
                                <td>
                                    {{ $fisaCaz->userVanzari->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Comercial
                                </td>
                                <td>
                                    {{ $fisaCaz->userComercial->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Tehnic
                                </td>
                                <td>
                                    {{ $fisaCaz->userTehnic->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Pacient
                                </td>
                                <td>
                                    <b>{{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume ?? ''}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Vârsta
                                </td>
                                <td>
                                    @if ($fisaCaz->pacient->cnp)
                                        @php
                                            $cnp = $fisaCaz->pacient->cnp;
                                            $anNastere = substr($cnp, 1, 2);
                                            $lunaNastere = substr($cnp, 3, 2);
                                            $ziNastere = substr($cnp, 5, 2);
                                            $dataNastere = new Carbon($anNastere . '-' . $lunaNastere . '-' . $ziNastere);
                                            $varsta = $dataNastere->diffInYears(Carbon::now());
                                        @endphp
                                        {{ $varsta }} ani
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Telefon
                                </td>
                                <td>
                                    {{ $fisaCaz->pacient->telefon ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Localitatea
                                </td>
                                <td>
                                    {{ $fisaCaz->pacient->localitate ?? '' }}
                                </td>
                            </tr>
                            @foreach ($fisaCaz->dateMedicale as $dataMedicala)
                                <tr>
                                    <td colspan="2">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Greutate
                                    </td>
                                    <td>
                                        {{ $dataMedicala->greutate }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Parte amputată
                                    </td>
                                    <td>
                                        {{ $dataMedicala->parte_amputata }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Amputație
                                    </td>
                                    <td>
                                        {{ $dataMedicala->amputatie }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Nivel de activitate
                                    </td>
                                    <td>
                                        {{ $dataMedicala->nivel_de_activitate }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Cauza amputației
                                    </td>
                                    <td>
                                        {{ $dataMedicala->cauza_amputatiei }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        A mai purtat proteză
                                    </td>
                                    <td>
                                        {{ ($dataMedicala->a_mai_purtat_proteza == '1') ? 'DA' : 'NU' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                       Tip proteză
                                    </td>
                                    <td>
                                        {{ $dataMedicala->tip_proteza }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Alte afecțiuni
                                    </td>
                                    <td>
                                        {{ $dataMedicala->alte_afectiuni }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Observații
                                    </td>
                                    <td>
                                        {{ $dataMedicala->observatii }}
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($fisaCaz->cerinte as $cerinta)
                                <tr>
                                    <td colspan="2">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Decizie CAS
                                    </td>
                                    <td>
                                        {{ ($cerinta->decizie_cas == '1') ? 'DA' : 'NU' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Buget disponibil
                                    </td>
                                    <td>
                                        {{ $cerinta->buget_disponibil }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Sursă buget
                                    </td>
                                    <td>
                                        {{ $cerinta->sursa_buget }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Cerințe particulare
                                    </td>
                                    <td>
                                        @if ($cerinta->cerinte_particulare_1)
                                            {{ $cerinta->cerinte_particulare_1 }}
                                            <br>
                                        @endif
                                        @if ($cerinta->cerinte_particulare_2)
                                            {{ $cerinta->cerinte_particulare_2 }}
                                            <br>
                                        @endif
                                        @if ($cerinta->cerinte_particulare_3)
                                            {{ $cerinta->cerinte_particulare_3 }}
                                            <br>
                                        @endif
                                        @if ($cerinta->cerinte_particulare_4)
                                            {{ $cerinta->cerinte_particulare_4 }}
                                            <br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Alte cerințe
                                    </td>
                                    <td>
                                        @if ($cerinta->alte_cerinte_1)
                                            {{ $cerinta->alte_cerinte_1 }}
                                            <br>
                                        @endif
                                        @if ($cerinta->alte_cerinte_2)
                                            {{ $cerinta->alte_cerinte_2 }}
                                            <br>
                                        @endif
                                        @if ($cerinta->alte_cerinte_3)
                                            {{ $cerinta->alte_cerinte_3 }}
                                            <br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4">
                                        Observații
                                    </td>
                                    <td>
                                        {{ $cerinta->observatii }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Comandă
                                </td>
                                <td>
                                    @foreach ($fisaCaz->fisiereComanda as $fisier)
                                        <a href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" style="text-decoration:cornflowerblue">
                                            {{ $fisier->nume }}</a>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Fișă Măsuri
                                </td>
                                <td>
                                    @foreach ($fisaCaz->fisiereFisaMasuri as $fisier)
                                        <a href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" style="text-decoration:cornflowerblue">
                                            {{ $fisier->nume }}</a>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row mb-2 px-2">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <a class="btn btn-secondary text-white rounded-3" href="{{ Session::get('fisaCazReturnUrl') }}">Înapoi</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
