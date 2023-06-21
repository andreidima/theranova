@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="culoare2 border border-secondary p-2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-clipboard-list me-1"></i>Recoltări sânge / Comenzi / {{ $recoltareSange->cod }}
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
                                    Produs
                                </td>
                                <td>
                                    {{ $recoltareSange->produs->nume ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Grupa
                                </td>
                                <td>
                                    {{ $recoltareSange->grupa->nume ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Data
                                </td>
                                <td>
                                    {{ $recoltareSange->data ? \Carbon\Carbon::parse($recoltareSange->data)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Cod
                                </td>
                                <td>
                                    {{ $recoltareSange->cod }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Tip
                                </td>
                                <td>
                                    {{ $recoltareSange->tip }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Cantitate
                                </td>
                                <td>
                                    {{ $recoltareSange->cantitate }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row mb-2 px-2">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <a class="btn btn-secondary text-white rounded-3" href="{{ Session::get('recoltareSangeReturnUrl') }}">Înapoi</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
