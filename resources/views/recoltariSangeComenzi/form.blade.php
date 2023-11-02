@csrf

<script type="application/javascript">
    recoltariSange = {!! json_encode($recoltariSange) !!}
    recoltariSangeAdaugateLaComandaIDuriVechi = {!! json_encode(old('recoltariSangeAdaugateLaComanda', $recoltareSangeComanda->recoltariSange->pluck('id'))) !!}

    recoltariSangeGrupe = {!! json_encode(($recoltariSangeGrupe) ?? []) !!}
    recoltariSangeProduse = {!! json_encode(($recoltariSangeProduse) ?? []) !!}
</script>
@php
    // echo old('recoltareSangeAdaugataLaComanda');
@endphp
<div class="row mb-0 px-3 d-flex justify-content-evenly border-radius: 0px 0px 40px 40px" id="recoltareSangeComanda">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-2 mb-4">
                <label for="comanda_nr" class="mb-0 ps-3">Comanda nr.</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('comanda_nr') ? 'is-invalid' : '' }}"
                    name="comanda_nr"
                    value="{{ old('comanda_nr', $recoltareSangeComanda->comanda_nr) }}"
                    required>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="aviz_nr" class="mb-0 ps-3">Aviz nr.<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('aviz_nr') ? 'is-invalid' : '' }}"
                    name="aviz_nr"
                    value="{{ old('aviz_nr', $recoltareSangeComanda->aviz_nr) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="unitate" class="mb-0 ps-3">Beneficiar<span class="text-danger">*</span></label>
                <select name="recoltari_sange_beneficiar_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_beneficiar_id') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($beneficiari as $beneficiar)
                        <option value="{{ $beneficiar->id }}" {{ ($beneficiar->id === intval(old('recoltari_sange_beneficiar_id', $recoltareSangeComanda->recoltari_sange_beneficiar_id ?? ''))) ? 'selected' : '' }}>{{ $beneficiar->nume }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 mb-4 text-center">
                <label for="data" class="mb-0 ps-0">Data<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $recoltareSangeComanda->data) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
        </div>
    </div>

    <div class="col-lg-12 px-3 py-3 mb-4 rounded-3" style="background-color: rgb(209, 233, 255)">
        <div v-if="cereriSange.length" class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-4">
                <div class="table-responsive rounded">
                    <table class="table table-striped table-hover rounded">
                        <thead class="text-white rounded">
                            <tr class="thead-danger" style="padding:2rem">
                                <th class="text-white" style="background-color: rgb(0, 116, 44)">#</th>
                                <th class="text-white" style="background-color: rgb(0, 116, 44)">Produs</th>
                                <th class="text-white" style="background-color: rgb(0, 116, 44)">Grupa</th>
                                <th class="text-white" style="background-color: rgb(0, 116, 44)">Cantitate</th>
                                <th class="text-white text-end" style="background-color: rgb(0, 116, 44)">Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cerereSange, index) in cereriSange">
                                <td align="">
                                    {{-- <input type="hidden" name="cereriSange[]" :value=.id> --}}
                                    @{{ index+1 }}
                                </td>
                                <td class="">
                                    {{-- @{{ recoltariSangeProduse[cerereSange.produs].nume }} --}}
                                    @{{ recoltariSangeProduse.find((produs) => produs.id==cerereSange.produs).nume }}
                                </td>
                                <td class="">
                                    {{-- @{{ recoltariSangeGrupe[cerereSange.grupa].nume }} --}}
                                </td>
                                <td class="">
                                    @{{ cerereSange.cantitate }}
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
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-4 text-center">
                <h4 class="mb-0 py-1 text-white" style="background-color: rgb(107, 126, 1)">Cerere sânge</h4>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="input-group mb-0 align-items-center">
                    <label for="recoltari_sange_produs_id" class="mb-0 ps-3">Produs<span class="text-danger">*</span></label>
                    <select name="recoltari_sange_produs_id"
                            v-model="cerereProdus"
                            class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_produs_id') ? 'is-invalid' : '' }}">
                        <option
                            v-for='produs in recoltariSangeProduse'
                            :value='produs.id'
                            >
                                @{{produs.nume}}
                        </option>
                    </select>
                    <label for="recoltari_sange_grupa_id" class="mb-0 ps-3">Grupa<span class="text-danger">*</span></label>
                    <select name="recoltari_sange_grupa_id"
                            v-model="cerereGrupa"
                            class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_grupa_id') ? 'is-invalid' : '' }}">
                        <option
                            v-for='grupa in recoltariSangeGrupe'
                            :value='grupa.id'
                            >
                                @{{grupa.nume}}
                        </option>
                    </select>
                    <span class="input-group-text">Cantitate</span>
                    <input
                        type="text"
                        class="form-control bg-white rounded-3 text-end"
                        size="10"
                        v-model="cerereCantitate"
                        ref='focusCerereCantitate'
                        autocomplete="off"
                        v-on:keydown.enter.prevent='cerereAdauga()'
                        >
                    <button type="button" class="btn btn-primary text-white" @click="cerereAdauga()">Adaugă</button>
                </div>
            </div>
        </div>

    <div class="col-lg-5 px-3 py-3 mb-4 rounded-3" style="background-color: rgb(247, 255, 209)">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-4 text-center">
                <h4 class="mb-0 py-1 text-white" style="background-color: rgb(107, 126, 1)">Căutare recoltări sânge</h4>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="input-group mb-0 align-items-center">
                    <span class="input-group-text">Cod</span>
                    <input
                        type="text"
                        class="form-control bg-white rounded-3"
                        v-model="recoltareSangeCod"
                        autocomplete="off"
                        ref='focusCod'
                        v-on:keydown.enter.prevent=''
                        v-on:keyup.enter="this.$refs.focusCantitate.focus();"
                        >
                    <span class="input-group-text">Cantitate</span>
                    <input
                        type="text"
                        class="form-control bg-white rounded-3 text-end"
                        size="10"
                        v-model="recoltareSangeCantitate"
                        ref='focusCantitate'
                        autocomplete="off"
                        v-on:keydown.enter.prevent='cautaRecoltariSangeCuDelay()'
                        >
                    <button type="button" class="btn btn-primary text-white" @click="cautaRecoltariSangeCuDelay()">Caută</button>
                </div>
            </div>
        </div>

        <div v-if="recoltariSangeCautate.length" class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-0">
                <div class="table-responsive rounded">
                    <table class="table table-striped table-hover rounded">
                        <thead class="text-white rounded">
                            <tr class="thead-danger" style="padding:2rem">
                                <th class="text-white" style="background-color: rgb(107, 126, 1)">#</th>
                                <th class="text-white" style="background-color: rgb(107, 126, 1)">Cod</th>
                                <th class="text-white" style="background-color: rgb(107, 126, 1)">Produs</th>
                                <th class="text-white" style="background-color: rgb(107, 126, 1)">Grupa</th>
                                <th class="text-white" style="background-color: rgb(107, 126, 1)">Cantitate</th>
                                <th class="text-white text-end" style="background-color: rgb(107, 126, 1)">Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(recoltareSangeCautata, index) in recoltariSangeCautate">
                                <td align="">
                                    @{{ index+1 }}
                                </td>
                                <td class="">
                                    @{{ recoltareSangeCautata.cod }}
                                </td>
                                <td class="">
                                    @{{ recoltareSangeCautata.produs.nume }}
                                </td>
                                <td class="">
                                    @{{ recoltareSangeCautata.grupa.nume }}
                                </td>
                                <td class="">
                                    @{{ recoltareSangeCautata.cantitate }}
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-primary btn-sm text-white" @click="adaugaRecoltareSangeLaComanda(recoltareSangeCautata.id)">Adaugă</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else="mesajCautareRecoltari" class="row mb-0 justify-content-center">
            {{-- <div v-html="mesajCautareRecoltari" class="col-lg-11 mb-0 bg-danger text-white rounded-3"> --}}
            <div v-html="mesajCautareRecoltari" class="col-lg-11 mb-0 rounded-3">
                {{-- @{{ mesajNegasireRecoltari }} --}}
            </div>
        </div>
    </div>

    <div class="col-lg-5 px-3 py-3 mb-4 rounded-3" style="background-color: rgb(198, 255, 226)">
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
    </div>

    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('recoltareSangeComandaReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
