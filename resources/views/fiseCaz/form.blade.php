@csrf

@php
    use \Carbon\Carbon;
@endphp

<script type="application/javascript">
    pacienti = {!! json_encode($pacienti) !!}
    pacientIdVechi = {!! json_encode(old('pacient_id', ($fisaCaz->pacient_id ?? "")) ?? "") !!}

    dateMedicale =  {!! json_encode(old('dateMedicale', $fisaCaz->dateMedicale()->get()) ?? []) !!}
    cerinte =  {!! json_encode(old('cerinte', $fisaCaz->cerinte()->get()) ?? []) !!}
</script>

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px">
    <div class="col-lg-12 px-4 py-2 mb-0">
        {{-- <div class="row px-2 pt-0 pb-1 mb-4 rounded-3 justify-content-center" style="background-color:#f9e9e8; border-left:6px solid; border-color:#ec8575"> --}}
        {{-- <div class="row mb-4 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #ec8575 solid; background-color:#fcf4f3"> --}}
        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)" id="datePicker">
            <div class="col-lg-2 mb-4">
                <label for="data" class="mb-0 ps-3">Evaluare<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $fisaCaz->data ?? Carbon::today()) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="compresie_manson" class="mb-0 ps-0">Compresie manșon</label>
                <vue-datepicker-next
                    data-veche="{{ old('compresie_manson', $fisaCaz->compresie_manson ?? '') }}"
                    nume-camp-db="compresie_manson"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="protezare" class="mb-0 ps-3">Protezare</label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $fisaCaz->protezare ?? '') }}"
                    nume-camp-db="protezare"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
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
            <div class="col-lg-6 mb-4">
                <label for="fisierComanda" class="mb-0 ps-3">Fișier comandă</label>
                <input type="file" name="fisierComanda" class="form-control rounded-3">
                @if($errors->has('fisierComanda'))
                    <span class="help-block text-danger">{{ $errors->first('fisierComanda') }}</span>
                @endif
                @if ($fisaCaz->fisiereComanda->first())
                    <small class="m-0 ps-3">
                        * Comandă încărcată la această fișă caz: {{ $fisaCaz->fisiereComanda->first()->nume ?? '' }}
                    </small>
                    <br>
                    <small class="m-0 ps-3">
                        * Dacă vrei să o înlocuiești, încarcă alt fișier, și cel care este acum se va șterge automat.
                    </small>
                @endif
            </div>
            <div class="col-lg-6 mb-4">
                <label for="fisierFisaMasuri" class="mb-0 ps-3">Fișier fisă măsuri</label>
                <input type="file" name="fisierFisaMasuri" class="form-control rounded-3">
                @if($errors->has('fisierFisaMasuri'))
                    <span class="help-block text-danger">{{ $errors->first('fisierFisaMasuri') }}</span>
                @endif
                @if ($fisaCaz->fisiereFisaMasuri->first())
                    <small class="m-0 ps-3">
                        * Fișă măsuri încărcată la această fișă caz: {{ $fisaCaz->fisiereFisaMasuri->first()->nume ?? '' }}
                    </small>
                    <br>
                    <small class="m-0 ps-3">
                        * Dacă vrei să o înlocuiești, încarcă alt fișier, și cel care este acum se va șterge automat.
                    </small>
                @endif
            </div>
        </div>
        {{-- <div class="row px-2 pt-0 mb-4" style="background-color:#eeeeff; border-left:6px solid; border-color:#6a6ba0; border-radius: 0px 0px 0px 0px" id="pacientAutocomplete"> --}}
        {{-- <div class="row mb-4 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #6a6ba0 solid; background-color:#f1f1fb" id="pacientAutocomplete"> --}}
        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5" id="pacientAutocomplete">
            <div class="col-lg-4 mb-4" style="position:relative;" v-click-out="() => pacientiListaAutocomplete = ''">
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
                        <span v-if="pacient_id" class="input-group-text text-danger" id="pacient_nume" v-on:click="pacient_id = null; pacient_nume = ''; pacient_telefon=''; pacient_localitate=''"><i class="fa-solid fa-xmark"></i></span>
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
                                {{-- pacient_data_nastere = new Date(pacient.data_nastere); pacient_data_nastere = pacient_data_nastere.toLocaleString('ro-RO', { dateStyle: 'short' }); --}}
                                pacient_telefon = pacient.telefon;
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
                <label for="telefon" class="mb-0 ps-3">Telefon</label>
                <input
                    type="text"
                    class="form-control rounded-3 {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
                    name="telefon"
                    placeholder=""
                    v-model="pacient_telefon"
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
        {{-- <div class="row px-2 pt-4 pb-1 justify-content-center" style="background-color:#B8FFB8; border-left:6px solid; border-color:mediumseagreen; border-radius: 0px 0px 0px 0px"> --}}
        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            {{-- <div class="col-lg-12 mb-4 text-center">
                <span class="fs-4 badge text-white" style="background-color:mediumseagreen;">Date medicale</span>
            </div> --}}
            <div class="col-lg-12 mb-0" id="fisaCazFormDateMedicale">
                <div class="row align-items-start mb-1" v-for="(dateMedical, index) in dateMedicale">
                    <div class="col-lg-2 mb-4">
                        <label for="greutate" class="mb-0 ps-3">Greutate<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('greutate') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][greutate]'"
                            v-model="dateMedicale[index].greutate">
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="parte_amputata" class="mb-0 ps-3">Parte amputată<span class="text-danger">*</span></label>
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
                        <label for="amputatie" class="mb-0 ps-3">Amputație<span class="text-danger">*</span></label>
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
                    <div class="col-lg-2 mb-4">
                        <label for="nivel_de_activitate" class="mb-0 ps-3">Nivel de activitate<span class="text-danger">*</span></label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('nivel_de_activitate') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][nivel_de_activitate]'"
                            v-model="dateMedicale[index].nivel_de_activitate">
                            <option selected></option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="cauza_amputatiei" class="mb-0 ps-3">Cauza amputației<span class="text-danger">*</span></label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('cauza_amputatiei') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][cauza_amputatiei]'"
                            v-model="dateMedicale[index].cauza_amputatiei">
                            <option selected></option>
                            <option value="Vascular">Vascular</option>
                            <option value="Diabet">Diabet</option>
                            <option value="Traumatic">Traumatic</option>
                            <option value="Congenital">Congenital</option>
                            <option value="Alte cauze">Alte cauze</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="a_mai_purtat_proteza" class="mb-0 ps-3">A mai putat proteza<span class="text-danger">*</span></label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('a_mai_purtat_proteza') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][a_mai_purtat_proteza]'"
                            v-model="dateMedicale[index].a_mai_purtat_proteza">
                            <option selected></option>
                            <option value="0">NU</option>
                            <option value="1">DA</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="tip_proteza" class="mb-0 ps-3">Tip proteză<span class="text-danger">*</span></label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('tip_proteza') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][tip_proteza]'"
                            v-model="dateMedicale[index].tip_proteza">
                            <option selected></option>
                            <option value="AK provizorie">AK provizorie</option>
                            <option value="AK definitivă">AK definitivă</option>
                            <option value="BK provizorie">BK provizorie</option>
                            <option value="BK definitivă">BK definitivă</option>
                            <option value="Modificări">Modificări</option>
                            <option value="PMS">PMS</option>
                            <option value="PPP">PPP</option>
                        </select>
                    </div>
                    <div class="col-lg-5 mb-4">
                        <label for="alte_afectiuni" class="mb-0 ps-3">Alte afecțiuni</label>
                        <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][alte_afectiuni]'"
                            v-model="dateMedicale[index].alte_afectiuni"
                            rows="3"></textarea>
                    </div>
                    <div class="col-lg-5 mb-4">
                        <label for="observatii" class="mb-0 ps-3">Observații</label>
                        <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                            :name="'dateMedicale[' + index + '][observatii]'"
                            v-model="dateMedicale[index].observatii"
                            rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row px-2 pt-4 pb-1 justify-content-center" style="background-color:lightyellow; border-left:6px solid; border-color:goldenrod" id="fisaCazFormCerinte"> --}}
        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5" id="fisaCazFormCerinte">
            {{-- <div class="col-lg-12 mb-4 text-center">
                <span class="fs-4 badge text-white" style="background-color:mediumseagreen;">Date medicale</span>
            </div> --}}
            <div class="col-lg-12 mb-0" v-for="(cerinta, index) in cerinte">
                <div class="row align-items-start">
                    <div class="col-lg-2 mb-4">
                        <label for="decizie_cas" class="mb-0 ps-3">Decizie CAS</label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('decizie_cas') ? 'is-invalid' : '' }}"
                            :name="'cerinte[' + index + '][decizie_cas]'"
                            v-model="cerinte[index].decizie_cas">
                            <option selected></option>
                            <option value="0">NU</option>
                            <option value="1">DA</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="buget_disponibil" class="mb-0 ps-3">Buget disponibil</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('buget_disponibil') ? 'is-invalid' : '' }}"
                            :name="'cerinte[' + index + '][buget_disponibil]'"
                            v-model="cerinte[index].buget_disponibil">
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="sursa_buget" class="mb-0 ps-3">Sursă buget</label>
                        <select class="form-select bg-white rounded-3 {{ $errors->has('sursa_buget') ? 'is-invalid' : '' }}"
                            :name="'cerinte[' + index + '][sursa_buget]'"
                            v-model="cerinte[index].sursa_buget">
                            <option selected></option>
                            <option value="CAS (doar CAS)">CAS (doar CAS)</option>
                            <option value="CAS + surse proprii">CAS + surse proprii</option>
                            <option value="Surse proprii">Surse proprii</option>
                            <option value="Alte surse">Alte surse</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-start mb-2">
                    <div class="col-lg-5 mb-4">
                        <div>
                            Cerințe particulare:
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][cerinte_particulare_1]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Deplasare limitată în interiorul locuinței" id="cerinte_particulare_1"
                                    :name="'cerinte[' + index + '][cerinte_particulare_1]'"
                                    v-model="cerinte[index].cerinte_particulare_1"
                                    true-value="Deplasare limitată în interiorul locuinței">
                                <label class="form-check-label" for="cerinte_particulare_1">Deplasare limitată în interiorul locuinței</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][cerinte_particulare_2]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Deplasare restrânsă în aer liber" id="cerinte_particulare_2"
                                    :name="'cerinte[' + index + '][cerinte_particulare_2]'"
                                    v-model="cerinte[index].cerinte_particulare_2"
                                    true-value="Deplasare restrânsă în aer liber">
                                <label class="form-check-label" for="cerinte_particulare_2">Deplasare restrânsă în aer liber</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][cerinte_particulare_3]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Deplasare nerestricționată în aer liber" id="cerinte_particulare_3"
                                    :name="'cerinte[' + index + '][cerinte_particulare_3]'"
                                    v-model="cerinte[index].cerinte_particulare_3"
                                    true-value="Deplasare nerestricționată în aer liber">
                                <label class="form-check-label" for="cerinte_particulare_3">Deplasare nerestricționată în aer liber</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][cerinte_particulare_4]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Deplasare nerestricționată în aer liber cu cerințe extrem de riguroase" id="cerinte_particulare_4"
                                    :name="'cerinte[' + index + '][cerinte_particulare_4]'"
                                    v-model="cerinte[index].cerinte_particulare_4"
                                    true-value="Deplasare nerestricționată în aer liber cu cerințe extrem de riguroase">
                                <label class="form-check-label" for="cerinte_particulare_4">Deplasare nerestricționată în aer liber cu cerințe extrem de riguroase</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-4">
                        <div>
                            Alte cerințe:
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][alte_cerinte_1]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Să ofere stabilitate" id="alte_cerinte_1"
                                    :name="'cerinte[' + index + '][alte_cerinte_1]'"
                                    v-model="cerinte[index].alte_cerinte_1"
                                    true-value="Să ofere stabilitate">
                                <label class="form-check-label" for="alte_cerinte_1">Să ofere stabilitate</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][alte_cerinte_2]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Să fie confortabilă" id="alte_cerinte_2"
                                    :name="'cerinte[' + index + '][alte_cerinte_2]'"
                                    v-model="cerinte[index].alte_cerinte_2"
                                    true-value="Să fie confortabilă">
                                <label class="form-check-label" for="alte_cerinte_2">Să fie confortabilă</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="hidden" :name="'cerinte[' + index + '][alte_cerinte_3]'" value="" />
                                <input class="form-check-input" type="checkbox" value="Să fie estetică" id="alte_cerinte_3"
                                    :name="'cerinte[' + index + '][alte_cerinte_3]'"
                                    v-model="cerinte[index].alte_cerinte_3"
                                    true-value="Să fie estetică">
                                <label class="form-check-label" for="alte_cerinte_3">Să fie estetică</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mb-4">
                        <label for="observatii" class="mb-0 ps-3">Observații</label>
                        <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                            :name="'cerinte[' + index + '][observatii]'"
                            v-model="cerinte[index].observatii"
                            rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-3">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('fisaCazReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
