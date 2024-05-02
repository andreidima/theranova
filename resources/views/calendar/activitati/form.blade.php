@csrf

<div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px">
    <div class="col-lg-12 px-4 py-2 mb-0 mx-auto">
        <div class="row mb-0" id="datePicker">
                <input
                    type="hidden"
                    name="fisa_caz_id"
                    value="{{ $activitate->fisa_caz_id }}">

            <div class="col-lg-12 mb-4">
                <label for="descriere" class="mb-0 ps-3">Descriere<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('descriere') ? 'is-invalid' : '' }}"
                    name="descriere"
                    placeholder=""
                    value="{{ old('descriere', $activitate->descriere) }}"
                    required>
            </div>
            <div class="col-lg-4 mb-4">
                <label for="calendar_id" class="mb-0 ps-3">Calendar<span class="text-danger">*</span></label>
                <select class="form-select bg-white rounded-3 {{ $errors->has('calendar_id') ? 'is-invalid' : '' }}" name="calendar_id">
                    <option selected></option>
                    @foreach ($calendare as $calendar)
                        <option value="{{ $calendar->id }}" {{ old('calendar_id', $activitate->calendar_id) == $calendar->id ? 'selected' : '' }}>{{ $calendar->nume }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 mb-4 text-center">
                <label for="data_inceput" class="mb-0 ps-0">Data început<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data_inceput', $activitate->data_inceput) }}"
                    nume-camp-db="data_inceput"
                    tip="datetime"
                    value-type="YYYY-MM-DD HH:mm"
                    format="DD.MM.YYYY HH:mm"
                    :latime="{ width: '160px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-4 mb-4 text-center">
                <label for="data_sfarsit" class="mb-0 ps-0">Data sfârșit</label>
                <vue-datepicker-next
                    data-veche="{{ old('data_sfarsit', $activitate->data_sfarsit) }}"
                    nume-camp-db="data_sfarsit"
                    tip="datetime"
                    value-type="YYYY-MM-DD HH:mm"
                    format="DD.MM.YYYY HH:mm"
                    :latime="{ width: '160px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-4 mb-4">
                <label for="cazare" class="mb-0 ps-3">Cazare</label>
                <select class="form-select bg-white rounded-3 {{ $errors->has('cazare') ? 'is-invalid' : '' }}" name="cazare">
                    <option selected></option>
                    <option value="Apartament 1" {{ old('cazare', $activitate->cazare) == "Apartament 1" ? 'selected' : '' }}>Apartament 1</option>
                    <option value="Apartament 2" {{ old('cazare', $activitate->cazare) == "Apartament 2" ? 'selected' : '' }}>Apartament 2</option>
                    <option value="Apartament 3" {{ old('cazare', $activitate->cazare) == "Apartament 3" ? 'selected' : '' }}>Apartament 3</option>
                </select>
            </div>
            <div class="col-lg-8 mb-4">
                <label for="observatii" class="mb-0 ps-3">Observații</label>
                <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                    name="observatii" rows="3">{{ old('observatii', $activitate->observatii) }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-0 d-flex justify-content-center">
                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('calendarActivitateReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
