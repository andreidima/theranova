@csrf

@php
    use \Carbon\Carbon;
    // dd(old('comenziComponente', $comanda->componente()->get()) ?? []);
@endphp

<script type="application/javascript">
    comenziComponente =  {!! json_encode(old('comenziComponente', $comanda->componente()->get()) ?? []) !!}
    // componente =  {!! json_encode(old('componente') ?? []) !!}
</script>

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px">
    <div class="col-lg-12 px-4 py-2 mb-0">
        <div class="row mb-4 pt-2 rounded-3 justify-content-between" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
            <div class="col-lg-7 mb-4">
                <label for="fisier" class="mb-0 ps-3">Fișier</label>
                <input type="file" name="fisier" class="form-control rounded-3">
                @if($errors->has('fisier'))
                    <span class="help-block text-danger">{{ $errors->first('fisier') }}</span>
                @endif
                @if ($comanda->fisiere->first())
                    <small class="m-0 ps-3">
                        * Comandă încărcată:
                        @foreach ($comanda->fisiere as $fisier)
                            <a class="small" href="/fisiere/{{ $fisier->id }}/deschide-descarca" target="_blank" style="text-decoration:cornflowerblue">
                                {{ $fisier->nume }}</a>{{ !$loop->last ? ', ' : '.' }}
                        @endforeach
                    </small>
                    <br>
                    <small class="m-0 ps-3">
                        * Dacă vrei să o înlocuiești, încarcă alt fișier, și cel care este acum se va șterge automat.
                    </small>
                @endif
            </div>
            <div class="col-lg-2 mb-4" id="datePicker">
                <label for="data" class="mb-0 ps-3">Data<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', $comanda->data) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-3 mb-4">
                <label for="sosita" class="mb-0 ps-3">Sosită</label>
                <select class="form-select bg-white rounded-3 {{ $errors->has('sosita') ? 'is-invalid' : '' }}" name="sosita">
                    <option selected></option>
                    <option value="1" {{ (old('sosita', $comanda->sosita ?? '') == "1") ? 'selected' : '' }}>DA</option>
                    <option value="0" {{ (old('sosita', $comanda->sosita ?? '') == "0") ? 'selected' : '' }}>NU</option>
                </select>
            </div>
        </div>
        <div class="row mb-4 pt-2 rounded-3 justify-content-center align-items-end" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
            <div class="col-lg-12 mb-0" id="comandaComponente">
                <div class="row align-items-end" v-for="(comandaComponenta, index) in comenziComponente">
                    <div class="col-lg-4 mb-4">
                        <input type="hidden" :name="'comenziComponente[' + index + '][id]'" v-model="comenziComponente[index].id">
                        <input type="hidden" :name="'comenziComponente[' + index + '][comanda_id]'" value="{{ $comanda->id }}">

                        <label for="producator" class="mb-0 ps-3">Producător<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('producator') ? 'is-invalid' : '' }}"
                            :name="'comenziComponente[' + index + '][producator]'"
                            v-model="comenziComponente[index].producator">
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="cod_produs" class="mb-0 ps-3">Cod produs<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('cod_produs') ? 'is-invalid' : '' }}"
                            :name="'comenziComponente[' + index + '][cod_produs]'"
                            v-model="comenziComponente[index].cod_produs">
                    </div>
                    <div class="col-lg-2 mb-4">
                        <label for="bucati" class="mb-0 ps-3">Bucăți<span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('bucati') ? 'is-invalid' : '' }}"
                            :name="'comenziComponente[' + index + '][bucati]'"
                            v-model="comenziComponente[index].bucati">
                    </div>
                    <div class="col-lg-2 mb-4 text-end">
                        <button type="button" class="btn btn-danger" @click="comenziComponente.splice(index,1)">Șterge</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-4 text-center">
                        <button type="button" ref="submit" class="btn btn-success text-white rounded-3"
                            v-on:click="this.comenziComponente.push({});">
                            Adaugă componentă
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('comandaReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
