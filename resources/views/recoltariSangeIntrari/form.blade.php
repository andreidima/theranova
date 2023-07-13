@csrf

<script type="application/javascript">
    recoltariSangeProduse = {!! json_encode(($recoltariSangeProduse) ?? []) !!}
    recoltariSangeGrupe = {!! json_encode(($recoltariSangeGrupe) ?? []) !!}

    nrPungi = {!! json_encode(0) !!}
    pungi = {!! json_encode(old('pungi', $recoltareSangeIntrare->recoltariSange->pluck('id'))) !!}
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
                <label for="recoltari_sange_beneficiar_id" class="mb-0 ps-3">Beneficiar<span class="text-danger">*</span></label>
                <select name="recoltari_sange_beneficiar_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_beneficiar_id') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($beneficiari as $beneficiar)
                        <option value="{{ $beneficiar->id }}" {{ ($beneficiar->id === intval(old('recoltari_sange_beneficiar_id', $recoltareSangeIntrare->recoltari_sange_beneficiar_id ?? ''))) ? 'selected' : '' }}>{{ $beneficiar->nume }}</option>
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
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-2 mb-4">
                <label for="nrPungi" class="mb-0 ps-3">Nr. pungi<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 text-end {{ $errors->has('nrPungi') ? 'is-invalid' : '' }}"
                    name="nrPungi"
                    v-model="nrPungi"
                    required>
            </div>
        </div>
    </div>
    <div v-if="nrPungi" class="col-lg-12 px-4 py-2 mb-4">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-0 mx-auto">
                <div class="row text-center">
                    <div class="col-lg-1 border border-1">
                        Punga
                    </div>
                    <div class="col-lg-2 border border-1">
                        Data
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
                </div>
            </div>
            {{-- <div v-for="(punga, index) in pungi" :key="punga" class="col-lg-12 mb-0 mx-auto"> --}}
            <div v-for="i in nrPungi" class="col-lg-12 mb-0 mx-auto">
                <div v-if="i > 0" class="row">
                    <div class="col-lg-1 border border-1 d-flex justify-content-center align-items-center">
                        @{{ i }}
                    </div>
                    <div class="col-lg-2 border border-1 mb-0 text-center">
                        {{-- <label for="data" class="mb-0 ps-0">Data<span class="text-danger">*</span></label> --}}
                        <vue-datepicker-next
                            data-veche="{{ old('data', $recoltareSangeIntrare->data) }}"
                            :nume-camp-db="'pungi[' + i + '][data]'"
                            tip="date"
                            value-type="YYYY-MM-DD"
                            format="DD.MM.YYYY"
                            :latime="{ width: '125px' }"
                        ></vue-datepicker-next>
                    </div>
                    <div class="col-lg-2 border border-1">
                        <select :name="'pungi[' + i + '][grupa]'" v-model="pungi[i].grupa" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_grupa_id') ? 'is-invalid' : '' }}">
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
                            :name="'pungi[' + i + '][cod]'"
                            v-model="pungi[i].cod">
                    </div>
                    <div class="col-lg-2 border border-1">
                        <select :name="'pungi[' + i + '][produs]'" v-model="pungi[i].produs" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_produs_id') ? 'is-invalid' : '' }}">
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
                            :name="'pungi[' + i + '][cantitate]'"
                            v-model="pungi[i].cantitate">
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
