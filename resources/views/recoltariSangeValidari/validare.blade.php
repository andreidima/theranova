@extends ('layouts.app')

<script type="application/javascript">
    rebuturi = {!! json_encode($rebuturi) !!}
    dataRebut = {!! json_encode(\Carbon\Carbon::today()->isoFormat('DD.MM.YYYY')) !!}
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-clipboard-list me-1"></i>Validează înregistrările din aplicație
                    </span>
                </div>

                @include ('errors')

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >

                    <div class="row mb-0 px-3 d-flex justify-content-evenly border-radius: 0px 0px 40px 40px" id="validareInregistrareInLaborator">
                        <div class="col-lg-12 px-3 py-3 mb-4 rounded-3" style="background-color: rgb(247, 255, 209)">
                            <div class="row mb-0 justify-content-center">
                                <div class="col-lg-12 mb-4 text-center">
                                    <h4 class="mb-0 py-1 text-white" style="background-color: rgb(107, 126, 1)">Scanează o pungă de sânge</h4>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <div class="input-group mb-0 align-items-center">
                                        <span class="input-group-text">Cod</span>
                                        <input
                                            type="text"
                                            class="form-control bg-white rounded-3"
                                            v-model="cod"
                                            autocomplete="off"
                                            ref='focusMe'
                                            {{-- v-on:change='console.log(cod)' --}}
                                            v-on:change='axiosCautaPungaCuDelay()'
                                            {{-- v-on:keyup='console.log(recoltareSangeCod)' --}}
                                            >
                                        <button type="button" class="btn btn-primary text-white" @click="">Caută</button>
                                    </div>
                                </div>
                            </div>


                            <div v-if="axiosMesajModificareRebut" class="row mb-0 justify-content-center">
                                <div v-html="axiosMesajModificareRebut" class="col-lg-11 mb-1 py-2 rounded-3 bg-danger text-white text-center">
                                </div>
                            </div>

                            <div v-if="recoltariSangeGasite && recoltariSangeGasite.length" class="row mb-0 justify-content-center">
                                <div class="col-lg-12 mb-0">
                                    <div class="table-responsive rounded">
                                        <table class="table table-striped table-hover rounded">
                                            <thead class="text-white rounded">
                                                <tr class="thead-danger" style="padding:2rem">
                                                    <th class="text-white" style="background-color: rgb(107, 126, 1)">#</th>
                                                    <th class="text-white" style="background-color: rgb(107, 126, 1)">Cod</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)">Produs</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)">Grupa</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)">Cantitate</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)">Validată</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)">Acțiuni</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)" v-if="afisareInterfataRebut != 0">Rebut data</th>
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)" v-if="afisareInterfataRebut != 0">Rebut tip</th>
                                                    {{-- <th class="text-white text-center" style="background-color: rgb(107, 126, 1)" v-if="afisareInterfataRebut != 0">Rebutează data</th>
                                                    <th class="text-white text-end" style="background-color: rgb(107, 126, 1)" v-if="afisareInterfataRebut != 0">Rebutează tip</th> --}}
                                                    <th class="text-white text-center" style="background-color: rgb(107, 126, 1)" v-if="afisareInterfataRebut != 0">Rebutează data/ tip</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(recoltareSange, index) in recoltariSangeGasite">
                                                    <td align="text-center">
                                                        @{{ index+1 }}
                                                    </td>
                                                    <td class="">
                                                        @{{ recoltareSange.cod }}
                                                    </td>
                                                    <td class="text-center">
                                                        @{{ recoltareSange.produs.nume }}
                                                    </td>
                                                    <td class="text-center">
                                                        @{{ recoltareSange.grupa.nume }}
                                                    </td>
                                                    <td class="text-center">
                                                        @{{ recoltareSange.cantitate }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{-- <span v-if="recoltareSange.validat === 0" class="bg-danger text-white px-2 rounded-3">NU</span>
                                                        <span v-else="recoltareSange.validat === 1" class="bg-success text-white px-2 rounded-3" >DA</span> --}}
                                                        <span v-if="recoltareSange.validat === 0" class="text-danger"><b>NU</b></span>
                                                        <span v-else="recoltareSange.validat === 1" class="text-success"><b>DA</b></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" v-if="recoltareSange.validat === 0" class="btn btn-success btn-sm text-white me-1" @click="valideazaInvalideaza('valideaza',recoltareSange.id)">Validează</button>
                                                        <button type="button" v-if="recoltareSange.validat === 1" class="btn btn-danger btn-sm text-white me-1" @click="valideazaInvalideaza('invalideaza',recoltareSange.id)">Invalidează</button>
                                                        <button type="button" class="btn btn-warning btn-sm text-white"
                                                            @click="(afisareInterfataRebut === recoltareSange.id) ? (afisareInterfataRebut = 0) : (afisareInterfataRebut = recoltareSange.id)">
                                                            Rebut
                                                        </button>
                                                    </td>
                                                    <td v-if="recoltareSange.id == afisareInterfataRebut" class="text-center">
                                                        {{-- Formatarea datei in romana. Folosind split(',')[0] ramane doar data, fara timp --}}
                                                        @{{ recoltareSange.rebut_data ? new Date(recoltareSange.rebut_data).toLocaleString('ro-RO').split(',')[0] : '' }}
                                                    </td>
                                                    <td v-if="recoltareSange.id == afisareInterfataRebut" class="text-center">
                                                        @{{ recoltareSange.rebut ? recoltareSange.rebut.nume : 'NU' }}
                                                    </td>
                                                    <td v-if="recoltareSange.id == afisareInterfataRebut">
                                                        <div class="d-flex justify-content-center">
                                                            <input
                                                                type="text"
                                                                class="form-control bg-white rounded-3 {{ $errors->has('dataRebut') ? 'is-invalid' : '' }}"
                                                                style="width:120px"
                                                                name="dataRebut"
                                                                v-model="recoltareSange.dataRebut">
                                                            <select name="rebuturi" class="form-select bg-white rounded-3 {{ $errors->has('rebuturi') ? 'is-invalid' : '' }}"
                                                                style="width:120px"
                                                                v-model="idRebut"
                                                                {{-- @change="modificaRebutPunga(recoltareSange.id, recoltareSange.dataRebut, $event.target.value)" --}}
                                                                >
                                                                <option selected></option>
                                                                @foreach ($rebuturi as $rebut)
                                                                    <option value="{{ $rebut->id }}">{{ $rebut->nume }}</option>
                                                                @endforeach
                                                            </select>
                                                            <button type="button" class="btn btn-primary btn-sm text-white"
                                                                @click="modificaRebutPunga(recoltareSange.id, recoltareSange.dataRebut, idRebut)">
                                                                OK
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div v-else="mesajCautareRecoltari" class="row mb-0 justify-content-center">
                                <div v-html="mesajCautareRecoltari" class="col-lg-11 mb-0 rounded-3">
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-5 px-3 py-3 mb-4 rounded-3" style="background-color: rgb(198, 255, 226)">
                            <div class="row mb-0 justify-content-center">
                                <div class="col-lg-12 mb-0 text-center">
                                    <h4 class="mb-0 py-1 text-white" style="background-color: rgb(0, 116, 44)">Recoltări sânge adăugate la comandă</h4>
                                </div>
                            </div>
                            <div v-if="recoltariSangeAdaugateLaComanda.length" class="row mb-0 justify-content-center">
                                <div class="col-lg-12 mb-4">
                                    <div class="table-responsive rounded">
                                        <table class="table table-striped table-hover rounded">
                                            <thead class="text-white rounded">
                                                <tr class="thead-danger" style="padding:2rem">
                                                    <th class="text-white" style="background-color: rgb(0, 116, 44)">#</th>
                                                    <th class="text-white" style="background-color: rgb(0, 116, 44)">Cod</th>
                                                    <th class="text-white" style="background-color: rgb(0, 116, 44)">Produs</th>
                                                    <th class="text-white" style="background-color: rgb(0, 116, 44)">Grupa</th>
                                                    <th class="text-white" style="background-color: rgb(0, 116, 44)">Cantitate</th>
                                                    <th class="text-white text-end" style="background-color: rgb(0, 116, 44)">Acțiuni</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(recoltareSangeAdaugataLaComanda, index) in recoltariSangeAdaugateLaComanda">
                                                    <td align="">
                                                        <input type="hidden" name="recoltariSangeAdaugateLaComanda[]" :value=recoltareSangeAdaugataLaComanda.id>
                                                        @{{ index+1 }}
                                                    </td>
                                                    <td class="">
                                                        @{{ recoltareSangeAdaugataLaComanda.cod }}
                                                    </td>
                                                    <td class="">
                                                        @{{ recoltareSangeAdaugataLaComanda.produs.nume }}
                                                    </td>
                                                    <td class="">
                                                        @{{ recoltareSangeAdaugataLaComanda.grupa.nume }}
                                                    </td>
                                                    <td class="">
                                                        @{{ recoltareSangeAdaugataLaComanda.cantitate }}
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-danger btn-sm text-white" @click="stergeRecoltareSangeLaComanda(recoltareSangeAdaugataLaComanda.id)">Șterge</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-lg-12 px-4 py-2 mb-0">
                            <div class="row">
                                <div class="col-lg-12 mb-2 d-flex justify-content-center">
                                    <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                                    <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('recoltareSangeComandaReturnUrl') }}">Renunță</a>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
