@php
    use \Carbon\Carbon;
@endphp

@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="culoare2 border border-secondary p-2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-file-medical me-1"></i>Fișe caz / {{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume }}
                    </span>
                </div>

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >

            @include ('errors')

                    <div class="table-responsive col-md-12 mx-auto">
                        <table class="table table-striped table-hover"
                        >
                            <tr>
                                <td class="pe-4">
                                    Data:
                                </td>
                                <td>
                                    {{ $fisaCaz->data ? Carbon::parse($fisaCaz->data)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Vânzări
                                </td>
                                <td>
                                    {{ $fisaCaz->userVanzari->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Comercial
                                </td>
                                <td>
                                    {{ $fisaCaz->userComercial->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Tehnic
                                </td>
                                <td>
                                    {{ $fisaCaz->userTehnic->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Pacient
                                </td>
                                <td>
                                    <b>{{ $fisaCaz->pacient->nume ?? '' }} {{ $fisaCaz->pacient->prenume ?? ''}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Data nașterii
                                </td>
                                <td>
                                    {{ $fisaCaz->pacient->data_nastere ? Carbon::parse($fisaCaz->pacient->data_nastere)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Localitatea
                                </td>
                                <td>
                                    {{ $fisaCaz->pacient->localitate ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row mb-2 px-2">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <a class="btn btn-secondary text-white rounded-3" href="{{ Session::get('fisaCazReturnUrl') }}">Înapoi</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
