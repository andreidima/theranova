@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="culoare2 border border-secondary p-2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-fa-person-crane me-1"></i>Pacienți / {{ $pacient->nume }} {{ $pacient->prenume }}
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
                                    Nume
                                </td>
                                <td>
                                    {{ $pacient->nume }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Prenume
                                </td>
                                <td>
                                    {{ $pacient->prenume }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Data naștere:
                                </td>
                                <td>
                                    {{ $pacient->data_nastere ? \Carbon\Carbon::parse($pacient->data_nastere)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Sex
                                </td>
                                <td>
                                    {{ $pacient->sex == '1' ? 'M' : 'F' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Adresa
                                </td>
                                <td>
                                    {{ $pacient->adresa }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Localiate
                                </td>
                                <td>
                                    {{ $pacient->localitate }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Județ
                                </td>
                                <td>
                                    {{ $pacient->judet }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Cod poștal
                                </td>
                                <td>
                                    {{ $pacient->cod_postal }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Telefon
                                </td>
                                <td>
                                    {{ $pacient->telefon }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Email
                                </td>
                                <td>
                                    {{ $pacient->email }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Observații
                                </td>
                                <td>
                                    {{ $pacient->observatii }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row mb-2 px-2">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <a class="btn btn-secondary text-white rounded-3" href="{{ Session::get('pacientReturnUrl') }}">Înapoi</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
