@extends('layouts.app')

@section('content')
<div class="container" style="">
    <div class="row py-5 justify-content-center">
        <div class="card col-lg-3 p-0 shadow border culoare1-border">
            <div class="card-header culoare1">
                <div class="row">
                    <div class="col-lg-12 text-center fs-5 d-flex justify-content-between">
                        <span>{{ __('auth.Reset Password') }}</span>
                        <span>{{ config('app.name', 'Laravel') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                @if (session('status'))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2 text-center">
                            Folosește linkul primit în email pentru a reseta parola
                            <br><br>
                            Poți închide această pagină, sau poți reveni la <a class="link" href="{{ route('login') }}">pagina de login</a>
                        </div>
                        {{-- <div class="col-lg-12 mb-3 text-center">
                            <a class="btn btn-primary rounded-3" href="{{ route('login') }}">Înapoi la pagina de login</a>
                        </div> --}}
                @else
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text culoare1" id="inputGroupPrepend2">
                                        <i class="fas fa-user culoare1"></i>
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
                        <div class="row mb-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mb-2 shadow-sm rounded-3">
                                    {{ __('auth.Send Password Reset Link') }}
                                </button>
                                <a class="btn btn-secondary rounded-3" href="{{ route('login') }}">Renunță</a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
