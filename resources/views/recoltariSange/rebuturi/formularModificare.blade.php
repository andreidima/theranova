@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-trash-can me-1"></i>Setare rebut
                    </span>
                </div>

                @include ('errors')

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >
                    <form  class="needs-validation" novalidate method="POST" action="/recoltari-sange/rebuturi/modifica/{{ $recoltareSange->id }}">
                        @method('PATCH')
                        @csrf
                        <div class="row mb-0 px-3 d-flex border-radius: 0px 0px 40px 40px">
                            <div class="col-lg-8 mx-auto px-4 py-2 mb-0">
                                <div class="row mb-0 justify-content-center">
                                    <div class="col-md-5 mb-1">
                                        Produs:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <b>{{ $recoltareSange->produs->nume ?? '' }}</b>
                                    </div>
                                    <div class="col-md-5 mb-1">
                                        Grupa:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <b>{{ $recoltareSange->grupa->nume ?? '' }}</b>
                                    </div>
                                    <div class="col-md-5 mb-1">
                                        Data:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <b>{{ $recoltareSange->data ? \Carbon\Carbon::parse($recoltareSange->data)->isoFormat('DD.MM.YYYY') : '' }}</b>
                                    </div>
                                    <div class="col-md-5 mb-1">
                                        Cod:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <b>{{ $recoltareSange->cod }}</b>
                                    </div>
                                    <div class="col-md-5 mb-1">
                                        Tip:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <b>{{ $recoltareSange->tip }}</b>
                                    </div>
                                    <div class="col-md-5 mb-1">
                                        Cantitate:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <b>{{ $recoltareSange->cantitate }}</b>
                                    </div>
                                    <div class="col-md-5 mb-1">
                                        Rebut:
                                    </div>
                                    <div class="col-md-7 mb-4">
                                        <input
                                            type="text"
                                            class="form-control bg-white rounded-3 {{ $errors->has('rebut') ? 'is-invalid' : '' }}"
                                            name="rebut"
                                            value="{{ old('rebut', $recoltareSange->rebut) }}"
                                            required>
                                    </div>
                                    <div class="col-lg-12 px-4 py-2 mb-0">
                                        <div class="row">
                                            <div class="col-lg-12 mb-1 d-flex justify-content-center">
                                                <button type="submit" ref="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">Modifică</button>
                                                <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('recoltareSangeRebutReturnUrl') }}">Renunță</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
