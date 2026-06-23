@csrf

@php
    use Carbon\Carbon;

    $liniiFormData = old('linii');
    if (is_null($liniiFormData)) {
        $liniiFormData = $oferta->linii->map(function ($linie) {
            return $linie->only(['id', 'produs_prospectare_id', 'denumire_produs', 'descriere', 'cantitate', 'pret_unitar', 'valoare_linie']);
        })->toArray();
    }
@endphp

<script type="application/javascript">
    const produseProspectare = {!! json_encode($produse->map(fn ($produs) => $produs->only(['id', 'denumire', 'descriere', 'pret_end_user']))) !!};
    const ofertaProspectareLiniiVechi = {!! json_encode($liniiFormData ?? []) !!};
    const ofertaProspectareAmputatiiVechi = {!! json_encode($amputatiiFormData ?? []) !!};
    const ofertaProspectareValoriVechi = {!! json_encode([
        'decontare_cas' => (int) old('decontare_cas', $oferta->decontare_cas ? 1 : 0),
        'buget_disponibil' => old('buget_disponibil', $oferta->buget_disponibil ?? 0),
        'discount_aditional' => old('discount_aditional', $oferta->discount_aditional ?? 0),
    ]) !!};
</script>

<div class="row mb-0 px-3 d-flex" id="ofertaProspectareForm">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
            <div class="col-lg-4 mb-4">
                <label class="mb-0 ps-3">Nume client<span class="text-danger">*</span></label>
                <input type="text" name="nume_client" class="form-control bg-white rounded-3" value="{{ old('nume_client', $oferta->nume_client) }}" required>
            </div>
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Telefon<span class="text-danger">*</span></label>
                <input type="text" name="telefon" class="form-control bg-white rounded-3" value="{{ old('telefon', $oferta->telefon) }}" required>
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Email</label>
                <input type="email" name="email" class="form-control bg-white rounded-3" value="{{ old('email', $oferta->email) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Sursa</label>
                <input type="text" name="sursa" class="form-control bg-white rounded-3" value="{{ old('sursa', $oferta->sursa) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Localitate</label>
                <input type="text" name="localitate" class="form-control bg-white rounded-3" value="{{ old('localitate', $oferta->localitate) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Judet<span class="text-danger">*</span></label>
                <input type="text" name="judet" class="form-control bg-white rounded-3" value="{{ old('judet', $oferta->judet) }}" required>
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Data ofertei</label>
                <input type="date" name="data_ofertei" class="form-control bg-white rounded-3" value="{{ old('data_ofertei', optional($oferta->data_ofertei)->format('Y-m-d') ?? Carbon::today()->format('Y-m-d')) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Valabila pana la</label>
                <input type="date" name="valabila_pana_la" class="form-control bg-white rounded-3" value="{{ old('valabila_pana_la', optional($oferta->valabila_pana_la)->format('Y-m-d') ?? Carbon::today()->addDays(14)->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-4 mb-4">
                <label class="mb-0 ps-3">Tip lucrare solicitata</label>
                <input type="text" name="tip_lucrare_solicitata" class="form-control bg-white rounded-3" value="{{ old('tip_lucrare_solicitata', $oferta->tip_lucrare_solicitata) }}">
            </div>
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Greutate</label>
                <input type="text" name="greutate" class="form-control bg-white rounded-3" value="{{ old('greutate', $oferta->greutate) }}">
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">Nivel activitate</label>
                <select name="nivel_de_activitate" class="form-select bg-white rounded-3">
                    <option value=""></option>
                    @foreach (['I', 'II', 'III', 'IV'] as $value)
                        <option value="{{ $value }}" {{ old('nivel_de_activitate', $oferta->nivel_de_activitate) === $value ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 mb-4">
                <label class="mb-0 ps-3">A mai purtat</label>
                <select name="a_mai_purtat_proteza" class="form-select bg-white rounded-3">
                    <option value=""></option>
                    <option value="0" {{ old('a_mai_purtat_proteza', $oferta->a_mai_purtat_proteza) === 0 || old('a_mai_purtat_proteza', $oferta->a_mai_purtat_proteza) === '0' ? 'selected' : '' }}>NU</option>
                    <option value="1" {{ old('a_mai_purtat_proteza', $oferta->a_mai_purtat_proteza) === 1 || old('a_mai_purtat_proteza', $oferta->a_mai_purtat_proteza) === '1' ? 'selected' : '' }}>DA</option>
                </select>
            </div>
            <div class="col-lg-12 mb-2 d-flex justify-content-between align-items-center">
                <span class="fw-bold">Amputatii</span>
                <button type="button" class="btn btn-sm btn-success text-white rounded-3" @click="adaugaAmputatie">+</button>
            </div>
            <div class="col-lg-12">
                <div class="row align-items-end mb-2" v-for="(amputatie, index) in amputatii" :key="'amputatie-' + index">
                    <input type="hidden" :name="'amputatii[' + index + '][id]'" v-model="amputatii[index].id">
                    <div class="col-lg-4 mb-2">
                        <label class="mb-0 ps-3">Parte amputata</label>
                        <select class="form-select bg-white rounded-3" :name="'amputatii[' + index + '][parte_amputata]'" v-model="amputatii[index].parte_amputata">
                            <option value=""></option>
                            <option value="Stânga">Stânga</option>
                            <option value="Dreapta">Dreapta</option>
                            <option value="Bilateral">Bilateral</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label class="mb-0 ps-3">Amputatie</label>
                        <select class="form-select bg-white rounded-3" :name="'amputatii[' + index + '][amputatie]'" v-model="amputatii[index].amputatie">
                            <option value=""></option>
                            <option value="" disabled style="color:white; background-color: gray;">Membru Inferior</option>
                            <option value="Parțială picior">Parțială picior</option>
                            <option value="Gambă">Gambă</option>
                            <option value="Dezarticulație genunchi">Dezarticulație genunchi</option>
                            <option value="Coapsă">Coapsă</option>
                            <option value="Dezarticulație șold">Dezarticulație șold</option>
                            <option value="" disabled style="color:white; background-color: gray;">Membru superior</option>
                            <option value="Deget">Deget</option>
                            <option value="Mână">Mână</option>
                            <option value="Articulație pumn">Articulație pumn</option>
                            <option value="Antebraț">Antebraț</option>
                            <option value="Cot">Cot</option>
                            <option value="Braț">Braț</option>
                            <option value="Dezarticulație umăr">Dezarticulație umăr</option>
                            <option value="" disabled style="color:white; background-color: gray;"></option>
                            <option value="Sân">Sân</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-2 text-end">
                        <button type="button" class="btn btn-danger" @click="amputatii.splice(index, 1)" :disabled="amputatii.length === 1">Sterge</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
            <div class="col-lg-12 mb-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold">Produse</span>
                <button type="button" class="btn btn-sm btn-success text-white rounded-3" @click="adaugaLinie">Adauga produs</button>
            </div>
            <div class="col-lg-12">
                <datalist id="produseProspectareList">
                    <option v-for="produs in produse" :value="produs.denumire"></option>
                </datalist>
                <div class="row align-items-end mb-2" v-for="(linie, index) in linii" :key="'linie-' + index">
                    <input type="hidden" :name="'linii[' + index + '][id]'" v-model="linii[index].id">
                    <input type="hidden" :name="'linii[' + index + '][produs_prospectare_id]'" v-model="linii[index].produs_prospectare_id">
                    <div class="col-lg-4 mb-2">
                        <label class="mb-0 ps-3">Produs</label>
                        <input type="text" class="form-control bg-white rounded-3" list="produseProspectareList" :name="'linii[' + index + '][denumire_produs]'" v-model="linii[index].denumire_produs" @change="alegeProdus(index)">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <label class="mb-0 ps-3">Cantitate</label>
                        <input type="number" min="1" class="form-control bg-white rounded-3" :name="'linii[' + index + '][cantitate]'" v-model.number="linii[index].cantitate">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <label class="mb-0 ps-3">Pret unitar</label>
                        <input type="number" min="0" class="form-control bg-white rounded-3" :name="'linii[' + index + '][pret_unitar]'" v-model.number="linii[index].pret_unitar">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <label class="mb-0 ps-3">Total</label>
                        <div class="form-control bg-light rounded-3">@{{ formatMoney(totalLinie(linie)) }} lei</div>
                    </div>
                    <div class="col-lg-1 mb-2 text-end">
                        <button type="button" class="btn btn-danger" @click="linii.splice(index, 1)">Sterge</button>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="mb-0 ps-3">Descriere</label>
                        <textarea class="form-control bg-white rounded-3" rows="2" :name="'linii[' + index + '][descriere]'" v-model="linii[index].descriere"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Decontare CAS</label>
                <input type="hidden" name="decontare_cas" value="0">
                <select name="decontare_cas" class="form-select bg-white rounded-3" v-model.number="decontare_cas">
                    <option value="0">NU</option>
                    <option value="1">DA</option>
                </select>
            </div>
            <div class="col-lg-2 mb-4" v-if="decontare_cas">
                <label class="mb-0 ps-3">Buget disponibil</label>
                <input type="number" min="0" name="buget_disponibil" class="form-control bg-white rounded-3" v-model.number="buget_disponibil">
            </div>
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Discount aditional</label>
                <input type="number" min="0" name="discount_aditional" class="form-control bg-white rounded-3" v-model.number="discount_aditional">
            </div>
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Subtotal</label>
                <div class="form-control bg-light rounded-3">@{{ formatMoney(subtotal) }} lei</div>
            </div>
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Suma de plata</label>
                <div class="form-control bg-light rounded-3">@{{ formatMoney(total) }} lei</div>
            </div>
            <div class="col-lg-2 mb-4">
                <label class="mb-0 ps-3">Avans 70%</label>
                <div class="form-control bg-light rounded-3">@{{ formatMoney(avans) }} lei</div>
            </div>
        </div>

        <div class="row mb-4 pt-2 rounded-3 justify-content-center" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
            <div class="col-lg-12 mb-4">
                <label class="mb-0 ps-3">Observatii interne</label>
                <textarea name="observatii_interne" rows="3" class="form-control bg-white rounded-3">{{ old('observatii_interne', $oferta->observatii_interne) }}</textarea>
            </div>
        </div>

        <div class="row pt-3">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" name="action" value="save" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <button type="submit" name="action" value="submit" class="btn btn-lg btn-success text-white me-3 rounded-3">{{ $submitText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('ofertaProspectareReturnUrl') ?? route('oferte-prospectare.index') }}">Renunta</a>
            </div>
        </div>
    </div>
</div>
