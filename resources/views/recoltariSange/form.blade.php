@csrf

{{-- Doar pentru adaugare --}}
@if (!str_contains(url()->current(), '/modifica'))
<script type="application/javascript">
    recoltariSangeProduse = {!! json_encode(($recoltariSangeProduse) ?? []) !!}
    tip = {!! json_encode(old('tip')) !!}
    nrPungi = {!! json_encode(intval(old('nrPungi'))) !!}
    pungi =  {!! json_encode(old('pungi') ?? []) !!}
    cantitatiPungiSange={!! json_encode(old('cantitatiPungiSange') ?? []) !!}
    cantitatiPungiSange={!! json_encode(old('cantitatiPungiSange') ?? []) !!}
</script>
@endif

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px" id="adaugareRecoltareSange">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-0 justify-content-center">
            {{-- <div class="col-lg-2 mb-4">
                <label for="recoltari_sange_produs_id" class="mb-0 ps-3">Produs{{ old('nrPungi') }}<span class="text-danger">*</span></label>
                <select name="recoltari_sange_produs_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_produs_id') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($recoltariSangeProduse as $recoltareSangeProdus)
                        <option value="{{ $recoltareSangeProdus->id }}" {{ ($recoltareSangeProdus->id === intval(old('recoltari_sange_produs_id', $recoltareSange->recoltari_sange_produs_id))) ? 'selected' : '' }}>{{ $recoltareSangeProdus->nume }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="col-lg-2 mb-4">
                <label for="recoltari_sange_grupa_id" class="mb-0 ps-3">Grupa<span class="text-danger">*</span></label>
                <select name="recoltari_sange_grupa_id" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_grupa_id') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($recoltariSangeGrupe as $recoltareSangeGrupa)
                        <option value="{{ $recoltareSangeGrupa->id }}" {{ ($recoltareSangeGrupa->id === intval(old('recoltari_sange_grupa_id', $recoltareSange->recoltari_sange_grupa_id))) ? 'selected' : '' }}>{{ $recoltareSangeGrupa->nume }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 mb-4 text-center">
                <label for="data" class="mb-0 ps-0">Data<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $recoltareSange->data) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="cod" class="mb-0 ps-3">Cod<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('cod') ? 'is-invalid' : '' }}"
                    name="cod"
                    {{-- v-model="cod" --}}
                    value="{{ old('cod', $recoltareSange->cod) }}"
                    required>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="tip" class="mb-0 ps-3">Tip<span class="text-danger">*</span></label>
                <select name="tip" v-model="tip" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_produs_id') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    <option value="S" {{ old('tip', $recoltareSange->tip) === 'S' ? 'selected' : '' }}>S</option>
                    <option value="D" {{ old('tip', $recoltareSange->tip) === 'D' ? 'selected' : '' }}>D</option>
                    <option value="D1" {{ old('tip', $recoltareSange->tip) === 'D1' ? 'selected' : '' }}>D1</option>
                    <option value="T" {{ old('tip', $recoltareSange->tip) === 'T' ? 'selected' : '' }}>T</option>
                </select>
            </div>
            {{-- Doar pentru modificare --}}
            @if (str_contains(url()->current(), '/modifica'))
                <div class="col-lg-2 mb-4">
                    <label for="cantitate" class="mb-0 ps-3">Cantitate<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control bg-white rounded-3 {{ $errors->has('cantitate') ? 'is-invalid' : '' }}"
                        name="cantitate"
                        {{-- v-model="cantitate" --}}
                        value="{{ old('cantitate', $recoltareSange->cantitate) }}"
                        required>
                </div>
            @endif
        </div>
    </div>



    {{-- <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row px-2 pt-4 pb-1 d-flex justify-content-center" style="background-color:#ddffff; border-left:6px solid; border-color:#2196F3; border-radius: 0px 0px 0px 0px">
            <div class="col-lg-12 mb-4 text-center">
                <span class="fs-4 badge text-white" style="background-color:#2196F3;">Pungi de sânge</span>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="row align-items-start mb-2" v-for="(recoltareSange, index) in recoltariSange"
                    :key="recoltare"
                    style="border:2px solid #2196F3;">
                    <div class="col-lg-4 mb-2" style="position:relative;">
                        <label for="nume" class="mb-0 ps-3">Punga @{{ index+1 }}<span class="text-danger">*</span></label>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <label for="cod" class="mb-0 ps-3">Cod</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('cod') ? 'is-invalid' : '' }}"
                            :name="'recoltariSange[' + index + '][cod]'"
                            v-model="recoltariSange[index].cod">
                    </div>
                    <div class="col-lg-4 mb-2">
                        <label for="tip" class="mb-0 ps-3">Tip</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('tip') ? 'is-invalid' : '' }}"
                            :name="'recoltariSange[' + index + '][tip]'"
                            v-model="recoltariSange[index].tip">
                    </div>
                    <div class="col-lg-4 mb-2">
                        <label for="cantitate" class="mb-0 ps-3">Cantitate</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('cantitate') ? 'is-invalid' : '' }}"
                            :name="'recoltariSange[' + index + '][cantitate]'"
                            v-model="recoltariSange[index].cantitate">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


{{-- Doar pentru adaugare --}}
@if (!str_contains(url()->current(), '/modifica'))
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-2 mb-4">
                <label for="nrPungi" class="mb-0 ps-3">Nr. pungi<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('nrPungi') ? 'is-invalid' : '' }}"
                    name="nrPungi"
                    v-model.number="nrPungi"
                    {{-- value="{{ old('nrPungi') }}" --}}
                    required>
            </div>
        </div>
    </div>

    <div v-if="pungi.length" class="col-lg-8 px-4 py-2 mb-4 rounded-3 mx-auto border border-secondary">
        <div class="row mb-0">
            <div class="col-lg-12 mb-2 text-center">
                <span class="fs-5 badge text-white culoare2">
                    Pungi de sânge
                </span>
            </div>
            <div class="col-lg-8 mb-0 mx-auto">
                <div class="row">
                    <div class="col-lg-4 border border-1 text-center">
                        {{-- Nr. pungă --}}
                    </div>
                    <div class="col-lg-4 border border-1 text-center">
                        Produs
                    </div>
                    <div class="col-lg-4 border border-1 text-center">
                        Cantitate
                    </div>
                </div>
            </div>
            <div v-for="(punga, index) in pungi" :key="punga" class="col-lg-8 mb-0 mx-auto">
                <div class="row">
                    <div class="col-lg-4 border border-1 d-flex justify-content-center align-items-center">
                        Punga @{{ index+1 }}
                    </div>
                    <div class="col-lg-4 border border-1">
                        <select :name="'pungi[' + index + '][produs]'" v-model="pungi[index].produs" class="form-select bg-white rounded-3 {{ $errors->has('recoltari_sange_produs_id') ? 'is-invalid' : '' }}">
                            <option disabled value=""></option>
                            <option
                                v-for='recoltare in recoltariSangeProduse'
                                :value='recoltare.nume'
                                >
                                    @{{recoltare.nume}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-4 border border-1">
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'pungi[' + index + '][cantitate]'"
                            v-model="pungi[index].cantitate">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('recoltareSangeReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
