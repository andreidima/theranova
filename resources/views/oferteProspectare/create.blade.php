@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="shadow-lg" style="border-radius: 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0 0;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-handshake me-1"></i>Adaugare oferta prospectare
                    </span>
                </div>

                @include('errors')

                <div class="card-body py-2 border border-secondary" style="border-radius: 0 0 40px 40px;">
                    <form class="needs-validation" novalidate method="POST" action="{{ route('oferte-prospectare.store') }}">
                        @include('oferteProspectare.form', [
                            'buttonText' => 'Salveaza oferta',
                            'submitText' => 'Trimite la aprobare',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
