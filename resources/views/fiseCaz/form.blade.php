@csrf

@php
    use \Carbon\Carbon;
@endphp


<script type="application/javascript">
    pacienti = {!! json_encode($pacienti) !!}
    pacientIdVechi = {!! json_encode(old('pacient_id', ($fisaCaz->pacient_id ?? "")) ?? "") !!}

    dateMedicale =  {!! json_encode(old('dateMedicale', $fisaCaz->dateMedicale()->get()) ?? []) !!}
</script>

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px" id="client">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row px-2 pt-4 pb-1 mb-0 justify-content-center" style="background-color:lightyellow; border-left:6px solid; border-color:goldenrod">
            <div class="col-lg-2 mb-4" id="datePicker">
                <label for="data" class="mb-0 ps-3">Dată fișă<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $fisaCaz->data ?? Carbon::today()) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-4 mb-4">
            </div>
            <div class="col-lg-2 mb-4">
                <label for="user_vanzari" class="mb-0 ps-3">Vânzări</label>
                <select name="user_vanzari" class="form-select bg-white rounded-3 {{ $errors->has('user_vanzari') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($useri->where('role', 1) as $user)
                        <option value="{{ $user->id }}" {{ ($user->id === intval(old('user_vanzari', $fisaCaz->user_vanzari ?? ''))) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="user_comercial" class="mb-0 ps-3">Comercial</label>
                <select name="user_comercial" class="form-select bg-white rounded-3 {{ $errors->has('user_comercial') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($useri->where('role', 2) as $user)
                        <option value="{{ $user->id }}" {{ ($user->id === intval(old('user_comercial', $fisaCaz->user_comercial ?? ''))) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="user_tehnic" class="mb-0 ps-3">Tehnic</label>
                <select name="user_tehnic" class="form-select bg-white rounded-3 {{ $errors->has('user_tehnic') ? 'is-invalid' : '' }}">
                    <option selected></option>
                    @foreach ($useri->where('role', 3) as $user)
                        <option value="{{ $user->id }}" {{ ($user->id === intval(old('user_tehnic', $fisaCaz->user_tehnic ?? ''))) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row px-2 pt-4 pb-1" style="background-color:#ddffff; border-left:6px solid; border-color:#2196F3; border-radius: 0px 0px 0px 0px" id="pacientAutocomplete">
            <div class="col-lg-3 mb-4">
                <label for="pacient_id" class="mb-0 ps-3">Pacient<span class="text-danger">*</span></label>
                <input
                    type="hidden"
                    v-model="pacient_id"
                    name="pacient_id">

                <div v-on:focus="autocompletePacienti();" class="input-group">
                    <div class="input-group-prepend d-flex align-items-center">
                        <span v-if="!pacient_id" class="input-group-text" id="pacient_nume">?</span>
                        <span v-if="pacient_id" class="input-group-text bg-success text-white" id="pacient_nume"><i class="fa-solid fa-check"></i></span>
                    </div>
                    <input
                        type="text"
                        v-model="pacient_nume"
                        v-on:focus="autocompletePacienti();"
                        v-on:keyup="autocompletePacienti(); this.pacient_id = '';"
                        class="form-control bg-white rounded-3 {{ $errors->has('pacient_nume') ? 'is-invalid' : '' }}"
                        name="pacient_nume"
                        placeholder=""
                        autocomplete="off"
                        aria-describedby="pacient_nume"
                        required>
                    <div class="input-group-prepend d-flex align-items-center">
                        <span v-if="pacient_id" class="input-group-text text-danger" id="pacient_nume" v-on:click="pacient_id = null; pacient_nume = ''; pacient_data_nastere=''; pacient_localitate=''"><i class="fa-solid fa-xmark"></i></span>
                    </div>
                    <div class="input-group-prepend ms-2 d-flex align-items-center">
                        <button type="submit" ref="submit" formaction="/fise-caz/adauga-resursa/pacient" class="btn btn-success text-white rounded-3 py-0 px-2"
                            style="font-size: 30px; line-height: 1.2;" title="Adaugă pacient nou">+</button>
                    </div>
                </div>
                <div v-cloak v-if="pacientiListaAutocomplete && pacientiListaAutocomplete.length" class="panel-footer">
                    <div class="list-group" style="max-height: 130px; overflow:auto;">
                        <button class="list-group-item list-group-item list-group-item-action py-0"
                            v-for="pacient in pacientiListaAutocomplete"
                            v-on:click="
                                pacient_id = pacient.id;
                                pacient_nume = pacient.nume + ' ' + pacient.prenume;
                                pacient_data_nastere = new Date(pacient.data_nastere); pacient_data_nastere = pacient_data_nastere.toLocaleString('ro-RO', { dateStyle: 'short' });
                                pacient_localitate = pacient.localitate;

                                pacientiListaAutocomplete = ''
                            ">
                                @{{ pacient.nume }} @{{ pacient.prenume }}
                        </button>
                    </div>
                </div>
                <small v-if="!pacient_id" class="ps-3">* Selectați un pacient</small>
                <small v-else class="ps-3 text-success">* Ați selectat un pacient</small>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="data_nastere" class="mb-0 ps-3">Data nașterii</label>
                <input
                    type="text"
                    class="form-control rounded-3 {{ $errors->has('data_nastere') ? 'is-invalid' : '' }}"
                    name="data_nastere"
                    placeholder=""
                    v-model="pacient_data_nastere"
                    disabled>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="localitate" class="mb-0 ps-3">Localitate</label>
                <input
                    type="text"
                    class="form-control rounded-3 {{ $errors->has('localitate') ? 'is-invalid' : '' }}"
                    name="localitate"
                    placeholder=""
                    v-model="pacient_localitate"
                    disabled>
            </div>
        </div>
        <div class="row px-2 pt-4 pb-1 justify-content-center" style="background-color:#B8FFB8; border-left:6px solid; border-color:mediumseagreen; border-radius: 0px 0px 0px 0px">
            <div class="col-lg-12 mb-4 text-center">
                <span class="fs-4 badge text-white" style="background-color:mediumseagreen;">Date medicale</span>
            </div>
            <div class="col-lg-12 mb-4" id="fisaCazFormDateMedicale">
                <div class="row align-items-start mb-2" v-for="(dateMedical, index) in dateMedicale" :key="dataMedicala">
                    <div class="col-lg-2 mb-4">
                        <label for="greutate" class="mb-0 ps-3">Greutate</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('greutate') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][greutate]'"
                            v-model="dateMedicale[index].greutate">
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="parte_amputata" class="mb-0 ps-3">Parte amputată</label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('parte_amputata') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][parte_amputata]'"
                            v-model="dateMedicale[index].parte_amputata">
                            <option selected></option>
                            <option value="Stânga">Stânga</option>
                            <option value="Dreapta">Dreapta</option>
                            <option value="Bilateral">Bilateral</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="amputatie" class="mb-0 ps-3">Amputație</label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('amputatie') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][amputatie]'"
                            v-model="dateMedicale[index].amputatie">
                            <option selected></option>
                            <option selected value="" disabled style="color:white; background-color: gray;">Membru Inferior</option>
                            <option value="Parțială picior">Parțială picior</option>
                            <option value="Gambă">Gambă</option>
                            <option value="Dezarticulație genunchi">Dezarticulație genunchi</option>
                            <option value="Coapsă">Coapsă</option>
                            <option value="Dezarticulație șold">Dezarticulație șold</option>
                            <option selected value="" disabled style="color:white; background-color: gray;">Membru superior</option>
                            <option value="Deget">Deget</option>
                            <option value="Mână">Mână</option>
                            <option value="Articulație pumn">Articulație pumn</option>
                            <option value="Antebraț">Antebraț</option>
                            <option value="Cot">Cot</option>
                            <option value="Braț">Braț</option>
                            <option value="Dezarticulație umăr">Dezarticulație umăr</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-8 mb-4">
            <label for="observatii" class="form-label mb-0 ps-3">Observații</label>
            <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                name="observatii" rows="3">{{ old('observatii', $pacient->observatii) }}</textarea>
        </div> --}}
        <div class="row pt-3">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('fisaCazReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
