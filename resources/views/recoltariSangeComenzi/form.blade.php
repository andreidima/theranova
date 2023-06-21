@csrf

<script type="application/javascript">
    recoltariSange = {!! json_encode($recoltariSange) !!}
    recoltariSangeAdaugateLaComandaIDuriVechi = {!! json_encode(old('recoltariSangeAdaugateLaComanda', $recoltareSangeComanda->recoltariSange->pluck('id'))) !!}
</script>
@php
    // echo old('recoltareSangeAdaugataLaComanda');
@endphp
<div class="row mb-0 px-3 d-flex justify-content-evenly border-radius: 0px 0px 40px 40px" id="recoltareSangeComanda">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-2 mb-4">
                <label for="numar" class="mb-0 ps-3">Număr<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('numar') ? 'is-invalid' : '' }}"
                    name="numar"
                    value="{{ old('numar', $recoltareSangeComanda->numar) }}"
                    required>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="unitate" class="mb-0 ps-3">Unitate<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('unitate') ? 'is-invalid' : '' }}"
                    name="unitate"
                    value="{{ old('unitate', $recoltareSangeComanda->unitate) }}"
                    required>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="localitate" class="mb-0 ps-3">Localitate<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('localitate') ? 'is-invalid' : '' }}"
                    name="localitate"
                    value="{{ old('localitate', $recoltareSangeComanda->localitate) }}"
                    required>
            </div>
            <div class="col-lg-2 mb-4">
                <label for="judet" class="mb-0 ps-3">Juteț<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('judet') ? 'is-invalid' : '' }}"
                    name="judet"
                    value="{{ old('judet', $recoltareSangeComanda->judet) }}"
                    required>
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

    <div class="col-lg-5 px-3 py-3 mb-4 rounded-3" style="background-color: rgb(247, 255, 209)">
        <div class="row mb-0 justify-content-center">
            <div class="col-lg-12 mb-4 text-center">
                <h4 class="mb-0 py-1 text-white" style="background-color: rgb(107, 126, 1)">Căutare recoltări sânge</h4>
            </div>
            <div class="col-lg-8 mb-4">
                <div class="input-group mb-0 align-items-center">
                    <span class="input-group-text">Cod recoltare</span>
                    <input
                        type="text"
                        class="col-lg-4 form-control bg-white rounded-3 {{ $errors->has('recoltareSangeCod') ? 'is-invalid' : '' }}"
                        v-model="recoltareSangeCod"
                        autocomplete="off">
                    <button type="button" class="btn btn-primary text-white" @click="cautaRecoltariSange()">Caută</button>
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
                                <th class="text-white" style="background-color: rgb(107, 126, 1)">Tip</th>
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
                                    @{{ recoltareSangeCautata.tip }}
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
                                <th class="text-white" style="background-color: rgb(0, 116, 44)">Tip</th>
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
                                    @{{ recoltareSangeAdaugataLaComanda.tip }}
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
