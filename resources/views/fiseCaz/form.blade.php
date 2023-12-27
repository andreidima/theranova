@csrf

<script type="application/javascript">
    pacienti = {!! json_encode($pacienti) !!}
    pacientIdVechi = {!! json_encode(old('pacient_id', ($fisaCaz->pacient_id ?? "")) ?? "") !!}
</script>

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px" id="client">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row px-2 pt-4 pb-1 mb-0 justify-content-center" style="background-color:lightyellow; border-left:6px solid; border-color:goldenrod">
        {{-- <div class="row"> --}}
            <div class="col-lg-6 mb-5 mx-auto" id="pacientAutocomplete">
                <label for="pacient_id" class="mb-0 ps-3">Pacient</label>
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
                        <span v-if="pacient_id" class="input-group-text text-danger" id="pacient_nume" v-on:click="pacient_id = null; pacient_nume = ''"><i class="fa-solid fa-xmark"></i></span>
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

                                pacientiListaAutocomplete = ''
                            ">
                                @{{ pacient.nume }} @{{ pacient.prenume }}
                        </button>
                    </div>
                </div>
                <small v-if="!pacient_id" class="ps-3">* Selectați un pacient</small>
                <small v-else class="ps-3 text-success">* Ați selectat un pacient</small>
            </div>
            <div class="col-lg-4 mb-4">
                <label for="greutate" class="mb-0 ps-3">Greutate</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('greutate') ? 'is-invalid' : '' }}"
                    name="greutate"
                    placeholder=""
                    value="{{ old('greutate', $fisaCaz->greutate) }}"
                    required>
            </div>
            {{-- <div class="col-lg-2 mb-4 text-center" id="datePicker">
                <label for="data" class="mb-0 ps-0">Data nașterii</label>
                <vue-datepicker-next
                    data-veche="{{ old('data_nastere', $pacient->data_nastere) }}"
                    nume-camp-db="data_nastere"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div> --}}
        {{-- <div class="row px-2 pt-4 pb-1 justify-content-center" style="background-color:#B8FFB8; border-left:6px solid; border-color:mediumseagreen; border-radius: 0px 0px 0px 0px">
            <div class="col-lg-8 mb-4">
                <label for="observatii" class="form-label mb-0 ps-3">Observații</label>
                <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                    name="observatii" rows="3">{{ old('observatii', $pacient->observatii) }}</textarea>
            </div>
        </div> --}}
        <div class="row pt-3">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('fisaCazReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
