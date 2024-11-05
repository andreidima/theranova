@csrf

@php
    use \Carbon\Carbon;
    // dd(old('incasari', $oferta->incasari()->get()) ?? []);
@endphp

<script type="application/javascript">
    incasariVechi =  {!! json_encode(old('incasari', $oferta->incasari()->get()) ?? []) !!}
</script>

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
            <div class="col-lg-6 mb-4">
                <label for="obiect_contract" class="mb-0 ps-3">Obiectul contractului<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('obiect_contract') ? 'is-invalid' : '' }}"
                    name="obiect_contract"
                    placeholder=""
                    value="{{ old('obiect_contract', $oferta->obiect_contract) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="pret" class="mb-0 ps-3">Preț<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('pret') ? 'is-invalid' : '' }}"
                    name="pret"
                    placeholder=""
                    value="{{ old('pret', $oferta->pret) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="acceptata" class="mb-0 ps-3">Acceptată</label>
                <select class="form-select bg-white rounded-3 {{ $errors->has('acceptata') ? 'is-invalid' : '' }}" name="acceptata">
                    <option selected></option>
                    <option value="1" {{ (old('acceptata', $oferta->acceptata ?? '') == "1") ? 'selected' : '' }}>DA</option>
                    <option value="0" {{ (old('acceptata', $oferta->acceptata ?? '') == "0") ? 'selected' : '' }}>NU</option>
                </select>
            </div>
            <div class="col-lg-12 mb-4">
                @if ($oferta->fisiere->first())
                    <p class="m-0 ps-3">
                        Fișier încărcat la această ofertă: <b>{{ $oferta->fisiere->first()->nume ?? '' }}</b>
                        <br>
                        Dacă vrei să-l înlocuiești, încarcă alt fișier, și cel care este acum se va șterge automat.
                    </p>
                @endif
                <input type="file" name="fisier" class="form-control rounded-3">
                @if($errors->has('fisier'))
                    <span class="help-block text-danger">{{ $errors->first('fisier') }}</span>
                @endif
            </div>
        </div>
        <div class="row mb-4 pt-2 rounded-3 justify-content-center" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-12 mb-4">
                <label for="observatii" class="form-label mb-0 ps-3">Observații</label>
                <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                    name="observatii" rows="3">{{ old('observatii', $oferta->observatii) }}</textarea>
            </div>
        </div>
        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
            <div class="col-lg-3 mb-4">
                <label for="contract_nr" class="mb-0 ps-3">Contract nr.</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('contract_nr') ? 'is-invalid' : '' }}"
                    name="contract_nr"
                    placeholder=""
                    value="{{ old('contract_nr', $oferta->contract_nr) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4" id="datePicker">
                <label for="contract_data" class="mb-0 ps-3">Contract data</label>
                <vue-datepicker-next
                    data-veche="{{ old('contract_data', $oferta->contract_data ?? null) }}"
                    nume-camp-db="contract_data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
        </div>


        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-12 mb-0" id="incasari">
                <div class="row align-items-start justify-content-center" v-if="incasari.length" v-for="(incasare, index) in incasari" :key="index">
                    <div class="col-lg-3 mb-4">
                        <input type="hidden" :name="'incasari[' + index + '][id]'" v-model="incasari[index].id">
                        <input type="hidden" :name="'incasari[' + index + '][oferta_id]'" value="{{ $oferta->id }}">

                        <label for="suma" class="mb-0 ps-3">Suma<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('suma') ? 'is-invalid' : '' }}"
                            :name="'incasari[' + index + '][suma]'"
                            v-model="incasari[index].suma">
                    </div>
                    <div class="col-lg-3 mb-4">
                        {{-- <label for="data" class="mb-0 ps-3">Data<span class="text-danger">*</span></label>
                        <vue-datepicker-next
                            :data-veche="incasari[index].data ?? ''"
                            :nume-camp-db="'incasari[' + index + '][data]'"
                            tip="date"
                            value-type="YYYY-MM-DD"
                            format="DD.MM.YYYY"
                            :latime="{ width: '125px' }"
                        ></vue-datepicker-next> --}}
                        <label for="data" class="mb-0 ps-3">Data<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('data') ? 'is-invalid' : '' }}"
                            :name="'incasari[' + index + '][data]'"
                            v-model="incasari[index].data">
                        <small class="ps-3">Ex:20.05.2024</small>
                    </div>
                    <div class="col-lg-2 mb-4 text-end">
                        <br>
                        <button type="button" class="btn btn-danger" @click="incasari.splice(index,1)">Șterge</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-4 text-center">
                        <button type="button" ref="submit" class="btn btn-success text-white rounded-3"
                            v-on:click="this.incasari.push({});">
                            Adaugă încasare
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row pt-3">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('ofertaReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
