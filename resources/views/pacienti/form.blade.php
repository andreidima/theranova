@csrf

@php
    use \Carbon\Carbon;
@endphp

<script type="application/javascript">
    apartinatori =  {!! json_encode(old('apartinatori', $pacient->apartinatori()->get()) ?? []) !!}
</script>

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-4 pt-2 rounded-3 justify-content-center" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-3 mb-4">
                <label for="user_responsabil" class="mb-0 ps-3">Responsabil</label>
                <select name="user_responsabil" class="form-select bg-white rounded-3 {{ $errors->has('user_responsabil') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($useri->where('role', 1) as $user)
                        <option value="{{ $user->id }}" {{ ($user->id === intval(old('user_responsabil', $pacient->user_responsabil ?? ''))) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="row px-2 pt-4 pb-1 mb-0 justify-content-center" style="background-color:lightyellow; border-left:6px solid; border-color:goldenrod"> --}}
        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
        {{-- <div class="row"> --}}
            <div class="col-lg-3 mb-4">
                <label for="nume" class="mb-0 ps-3">Nume<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('nume') ? 'is-invalid' : '' }}"
                    name="nume"
                    placeholder=""
                    value="{{ old('nume', $pacient->nume) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="prenume" class="mb-0 ps-3">Prenume<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('prenume') ? 'is-invalid' : '' }}"
                    name="prenume"
                    placeholder=""
                    value="{{ old('prenume', $pacient->prenume) }}"
                    required>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="telefon" class="mb-0 ps-3">Telefon</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
                    name="telefon"
                    placeholder=""
                    value="{{ old('telefon', $pacient->telefon) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label for="email" class="mb-0 ps-3">Email</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    name="email"
                    placeholder=""
                    value="{{ old('email', $pacient->email) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label for="cnp" class="mb-0 ps-3">CNP</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('cnp') ? 'is-invalid' : '' }}"
                    name="cnp"
                    placeholder=""
                    value="{{ old('cnp', $pacient->cnp) }}">
            </div>
            <div class="col-lg-2 mb-4">
                <label for="serie_numar_buletin" class="mb-0 ps-xxl-3">Serie Nr. buletin</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('serie_numar_buletin') ? 'is-invalid' : '' }}"
                    name="serie_numar_buletin"
                    placeholder=""
                    value="{{ old('serie_numar_buletin', $pacient->serie_numar_buletin) }}">
            </div>
            <div class="col-lg-2 mb-4" id="datePicker">
                <label for="data" class="mb-0 ps-xxl-2"><small>Data elib. buletin</small></label>
                <vue-datepicker-next
                    data-veche="{{ old('data_eliberare_buletin', $pacient->data_eliberare_buletin) }}"
                    nume-camp-db="data_eliberare_buletin"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-2 mb-4">
                <div class="text-center">
                    <label class="mb-0 ps-3">Sex</label>
                    <div class="d-flex py-1 justify-content-center">
                        <div class="form-check me-4">
                            <input class="form-check-input" type="radio" value="1" name="sex" id="sex_da"
                                {{ old('sex', $pacient->sex) == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="sex_da">M</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2" name="sex" id="sex_nu"
                                {{ old('sex', $pacient->sex) == '2' ? 'checked' : '' }}>
                            <label class="form-check-label" for="sex_nu">F</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="cum_a_aflat_de_theranova" class="mb-0 ps-xxl-3"><small>Cum a aflat de Theranova</small></label>
                <select class="form-select bg-white rounded-3 {{ $errors->has('cum_a_aflat_de_theranova') ? 'is-invalid' : '' }}"
                    name="cum_a_aflat_de_theranova">
                    <option selected></option>
                    <option value="Spital" {{ old('cum_a_aflat_de_theranova', $pacient->cum_a_aflat_de_theranova ?? '') == "Spital" ? 'selected' : '' }}>Spital</option>
                    <option value="Recomandare" {{ old('cum_a_aflat_de_theranova', $pacient->cum_a_aflat_de_theranova ?? '') == "Recomandare" ? 'selected' : '' }}>Recomandare</option>
                    <option value="Pacient vechi" {{ old('cum_a_aflat_de_theranova', $pacient->cum_a_aflat_de_theranova ?? '') == "Pacient vechi" ? 'selected' : '' }}>Pacient vechi</option>
                    <option value="Internet" {{ old('cum_a_aflat_de_theranova', $pacient->cum_a_aflat_de_theranova ?? '') == "Internet" ? 'selected' : '' }}>Internet</option>
                    <option value="theranova.ro" {{ old('cum_a_aflat_de_theranova', $pacient->cum_a_aflat_de_theranova ?? '') == "theranova.ro" ? 'selected' : '' }}>theranova.ro</option>
                </select>
            </div>
        </div>
        {{-- <div class="row px-2 pt-4 pb-1 justify-content-center" style="background-color:#ddffff; border-left:6px solid; border-color:#2196F3; border-radius: 0px 0px 0px 0px"> --}}
        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-12 mb-4">
                <label for="adresa" class="mb-0 ps-3">Adresa</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('adresa') ? 'is-invalid' : '' }}"
                    name="adresa"
                    placeholder=""
                    value="{{ old('adresa', $pacient->adresa) }}">
            </div>
            <div class="col-lg-4 mb-4">
                <label for="localitate" class="mb-0 ps-3">Localitate</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('localitate') ? 'is-invalid' : '' }}"
                    name="localitate"
                    placeholder=""
                    value="{{ old('localitate', $pacient->localitate) }}">
            </div>
            <div class="col-lg-4 mb-4">
                <label for="judet" class="mb-0 ps-3">Județ</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('judet') ? 'is-invalid' : '' }}"
                    name="judet"
                    placeholder=""
                    value="{{ old('judet', $pacient->judet) }}">
            </div>
            {{-- <div class="col-lg-4 mb-4">
                <label for="cod_postal" class="mb-0 ps-3">Cod poștal</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('cod_postal') ? 'is-invalid' : '' }}"
                    name="cod_postal"
                    placeholder=""
                    value="{{ old('cod_postal', $pacient->cod_postal) }}">
            </div> --}}
        </div>
        {{-- <div class="row px-2 pt-4 pb-1 justify-content-center" style="background-color:#B8FFB8; border-left:6px solid; border-color:mediumseagreen; border-radius: 0px 0px 0px 0px"> --}}
        <div class="row mb-4 pt-2 rounded-3 justify-content-center" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5" id="pacientFormApartinatori">
            <div class="col-lg-12 mb-4 text-center">
                <button type="button" class="btn btn-success text-white rounded-3" v-on:click="apartinatori.push({})">Adaugă aparținător</button>
            </div>
            <div class="col-lg-12 mb-0">
                <div class="row mx-1 mb-2" v-for="(apartinator, index) in apartinatori" style="border:1px solid #e66800;">
                    <div class="col-lg-4 mb-4">
                        <input
                            type="hidden"
                            :name="'apartinatori[' + index + '][id]'"
                            v-model="apartinatori[index].id"
                            >

                        <label for="nume" class="mb-0 ps-3">Nume<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'apartinatori[' + index + '][nume]'"
                            v-model="apartinatori[index].nume">
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="prenume" class="mb-0 ps-3">Prenume<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'apartinatori[' + index + '][prenume]'"
                            v-model="apartinatori[index].prenume">
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="telefon" class="mb-0 ps-3">Telefon</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'apartinatori[' + index + '][telefon]'"
                            v-model="apartinatori[index].telefon">
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="email" class="mb-0 ps-3">Email</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'apartinatori[' + index + '][email]'"
                            v-model="apartinatori[index].email">
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="grad_rudenie" class="mb-0 ps-3">Grad rudenie</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3"
                            :name="'apartinatori[' + index + '][grad_rudenie]'"
                            v-model="apartinatori[index].grad_rudenie">
                    </div>
                    <div class="col-lg-4 mb-4 mh-100 d-flex justify-content-end align-items-end">
                        <button type="button" class="btn btn-danger" v-on:click="apartinatori.splice(index, 1);">Șterge</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4 pt-2 rounded-3 justify-content-center" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-8 mb-4">
                <label for="observatii" class="form-label mb-0 ps-3">Observații</label>
                <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                    name="observatii" rows="3">{{ old('observatii', $pacient->observatii) }}</textarea>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('pacientReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
