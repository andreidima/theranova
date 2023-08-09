@csrf
@php
    // dd($recoltareSangeIntrare->recoltariSange);
@endphp
<script type="application/javascript">
    recoltariSangeProduse = {!! json_encode(($recoltariSangeProduse) ?? []) !!}
    recoltariSangeGrupe = {!! json_encode(($recoltariSangeGrupe) ?? []) !!}

    // nrPungi = {!! json_encode(old('nrPungi')) !!}
    // pungi = {!! json_encode(old('pungi', $recoltareSangeIntrare->recoltariSange->pluck('id'))) !!}
    // pungi = {!! json_encode((array) old('pungi', [])) !!}
    pungi = {!! json_encode(old('pungi', $recoltareSangeIntrare->recoltariSange) ?? []) !!}
</script>

<div class="row mb-0 px-3 d-flex justify-content-evenly border-radius: 0px 0px 40px 40px" id="recoltareSangeIntrare">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-2 mb-4">
                <label for="bon_nr" class="mb-0 ps-3">Bon nr.<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('bon_nr') ? 'is-invalid' : '' }}"
                    name="bon_nr"
                    value="{{ old('bon_nr', $recoltareSangeIntrare->bon_nr) }}"
                    required>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="aviz_nr" class="mb-0 ps-3">Aviz nr.<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('aviz_nr') ? 'is-invalid' : '' }}"
                    name="aviz_nr"
                    value="{{ old('aviz_nr', $recoltareSangeIntrare->aviz_nr) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="recoltari_sange_expeditor_id" class="mb-0 ps-3">Expeditor<span class="text-danger">*</span></label>
                <select name="recoltari_sange_expeditor_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_expeditor_id') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($expeditori as $expeditor)
                        <option value="{{ $expeditor->id }}" {{ ($expeditor->id === intval(old('recoltari_sange_expeditor_id', $recoltareSangeIntrare->recoltari_sange_expeditor_id ?? ''))) ? 'selected' : '' }}>{{ $expeditor->nume }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 mb-4 text-center">
                <label for="data" class="mb-0 ps-0">Data<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $recoltareSangeIntrare->data) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
        </div>
    </div>
    <div class="col-lg-12 px-4 py-2 mb-4">
        <div class="row mb-2 justify-content-center">
            <div class="col-auto mb-0 py-2 d-flex align-items-center" style="background-color: rgb(220, 218, 253)">
                <label for="nrPungi" class="mb-0">Nr. pungi<span class="text-danger">*</span></label>
            </div>
            <div class="col-auto mb-0 py-2 d-flex align-items-center" style="background-color: rgb(220, 218, 253)">
                <input
                    type="text"
                    class="form-control bg-white rounded-3 text-end {{ $errors->has('nrPungi') ? 'is-invalid' : '' }}"
                    {{-- name="nrPungi" --}}
                    v-model="nrPungi"
                    style="width: 100px;"
                    required>
            </div>
            <div class="col-auto mb-0 py-2" style="background-color: rgb(220, 218, 253)">
                <button type="button" class="btn btn-success text-white rounded-3" @click="adaugaPungi">
                    Adaugă în listă
                </button>
            </div>
        </div>
        <div v-if="nrPungi > 100" class="row mb-2 justify-content-center">
            <div class="col-auto mb-0 py-2 d-flex align-items-center">
                <div>
                    <div class="alert alert-danger mb-0 text-center" role="alert">
                        Introduceți un număr de pungi mai mic de 100.
                        <br>
                        Dacă aveți totuși de introdus mai multe, introduceți în mai multe rânduri.
                    </div>
                </div>
            </div>
        </div>
        {{-- <div v-else="!Number.isInteger(parseInt(this.nrPungi))" class="row mb-2 justify-content-center">
            <div class="col-auto mb-0 py-2 d-flex align-items-center">
                <div>
                    <div class="alert alert-danger mb-0 text-center" role="alert">
                        Introduceți cifre
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    {{-- <div v-if="parseInt(this.nrPungi) > 0" class="col-lg-12 px-4 py-2 mb-4"> --}}
    <div v-if="pungi" class="col-lg-12 px-4 py-2 mb-4">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-0 mx-auto">
                <div class="row text-center">
                    <div class="col-lg-1 border border-1">
                        Punga
                    </div>
                    <div class="col-lg-2 border border-1">
                        Data expirare
                    </div>
                    <div class="col-lg-2 border border-1">
                        Grupa
                    </div>
                    <div class="col-lg-2 border border-1">
                        Cod
                    </div>
                    <div class="col-lg-2 border border-1">
                        Produs
                    </div>
                    <div class="col-lg-2 border border-1">
                        Cantitate
                    </div>
                    <div class="col-lg-1 border border-1">
                        Șterge
                    </div>
                </div>
            </div>
            <div v-for="(punga, index) in pungi" :key="punga" class="col-lg-12 mb-0 mx-auto">
            {{-- <div v-for="index in parseInt(nrPungi)" class="col-lg-12 mb-0 mx-auto"> --}}
                <div class="row">
                    <div class="col-lg-1 border border-1 d-flex justify-content-center align-items-center">
                        <input
                            type="hidden"
                            :name="'pungi[' + index + '][id]'"
                            v-model="pungi[index].id">
                        @{{ index+1 }}
                    </div>
                    <div class="col-lg-2 border border-1 mb-0 text-center">
                        <vue-datepicker-next
                            :data-veche="pungi[index].data_expirare ?? ''"
                            :nume-camp-db="'pungi[' + index + '][data_expirare]'"
                            tip="date"
                            value-type="YYYY-MM-DD"
                            format="DD.MM.YYYY"
                            :latime="{ width: '125px' }"
                        ></vue-datepicker-next>
                        {{-- <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'pungi[' + index + '][data]'"
                            v-model="pungi[index].data"> --}}
                    </div>
                    <div class="col-lg-2 border border-1">
                        <select :name="'pungi[' + index + '][recoltari_sange_grupa_id]'" v-model="pungi[index].recoltari_sange_grupa_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_grupa_id') ? 'is-invalid' : '' }}">
                            <option
                                v-for='grupa in recoltariSangeGrupe'
                                :value='grupa.id'
                                >
                                    @{{grupa.nume}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-2 border border-1">
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'pungi[' + index + '][cod]'"
                            v-model="pungi[index].cod">
                    </div>
                    <div class="col-lg-2 border border-1">
                        <select :name="'pungi[' + index + '][recoltari_sange_produs_id]'" v-model="pungi[index].recoltari_sange_produs_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_produs_id') ? 'is-invalid' : '' }}">
                            <option
                                v-for='produs in recoltariSangeProduse'
                                :value='produs.id'
                                >
                                    @{{produs.nume}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-2 border border-1">
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'pungi[' + index + '][cantitate]'"
                            v-model="pungi[index].cantitate">
                    </div>
                    <div class="col-lg-1 border border-1 d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-sm btn-danger text-white rounded-3" @click="stergePunga(index)">
                            Șterge
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('recoltareSangeIntrareReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
