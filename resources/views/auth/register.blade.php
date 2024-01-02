@extends('layouts.app')

@section('content')
<div class="container" style="">
    <div class="row py-5 justify-content-center">
        <div class="card col-lg-3 p-0 shadow border culoare1-border">
            <div class="card-header culoare1">
                <div class="row">
                    <div class="col-lg-12 text-center fs-5 d-flex justify-content-between">
                        <span>Înregistrare</span>
                        <span>{{ config('app.name', 'Laravel') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text culoare1" id="inputGroupPrepend2">
                                    <i class="fas fa-user culoare1"></i>
                                </span>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Nume">
                            </div>
                            @error('name')
                                <span class="text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text culoare1" id="inputGroupPrepend2">
                                    <i class="fa-solid fa-at culoare1"></i>
                                </span>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                                    placeholder="{{ __('auth.E-Mail Address') }}"
                                >
                            </div>
                            @error('email')
                                <span class="text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text culoare1" id="inputGroupPrepend2">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password"
                                    placeholder="{{ __('auth.Password') }}"
                                >
                            </div>
                            @error('password')
                                <span class="text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text culoare1" id="inputGroupPrepend2">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                    placeholder="Confirmă parola"
                                >
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary shadow-sm rounded-3">Înregistrează-te</button>
                            <a class="btn btn-secondary rounded-3" href="{{ route('login') }}">Renunță</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
