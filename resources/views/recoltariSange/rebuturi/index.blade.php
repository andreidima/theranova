@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-syringe me-1"></i>Recoltări sânge
                </span>
            </div>
            <div class="col-lg-6">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current()  }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-8">
                            <input type="text" class="form-control rounded-3" id="searchCod" name="searchCod" placeholder="Cod" value="{{ $searchCod }}">
                        </div>
                    </div>
                    <div class="row custom-search-form justify-content-center">
                        <div class="col-lg-4">
                            <button class="btn btn-sm w-100 btn-primary text-white border border-dark rounded-3" type="submit">
                                <i class="fas fa-search text-white me-1"></i>Caută
                            </button>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-sm w-100 btn-secondary text-white border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                                <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="{{ url()->current() }}/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă recoltare
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    {{-- <thead class="text-white rounded bg-danger" bgcolor="red"> --}}
                    <thead class="text-white rounded">
                        <tr class="thead-danger" style="padding:2rem">

            {{-- <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    <thead class="text-white rounded" style="background-color: #69A1B1">
                        <tr class="" style="padding:2rem"> --}}
                            <th class="text-white culoare2">#</th>
                            <th class="text-white culoare2">Produs</th>
                            <th class="text-white culoare2">Grupa</th>
                            <th class="text-white culoare2">Data</th>
                            <th class="text-white culoare2">Cod</th>
                            <th class="text-white culoare2">Tip</th>
                            <th class="text-white culoare2">Cantitate</th>
                            {{-- <th class="">Observații</th> --}}
                            <th class="text-white culoare2 text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recoltariSange as $recoltareSange)
                            <tr>
                                <td align="">
                                    {{ ($recoltariSange ->currentpage()-1) * $recoltariSange ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    {{ $recoltareSange->produs->nume ?? '' }}
                                </td>
                                <td class="">
                                    {{ $recoltareSange->grupa->nume ?? '' }}
                                </td>
                                <td class="">
                                    {{ $recoltareSange->data ? \Carbon\Carbon::parse($recoltareSange->data)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="">
                                    {{ $recoltareSange->cod }}
                                </td>
                                <td class="">
                                    {{ $recoltareSange->tip }}
                                </td>
                                <td class="">
                                    {{ $recoltareSange->cantitate }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $recoltareSange->path() }}" class="flex me-1">
                                            <span class="badge bg-success">Vizualizează</span>
                                        </a>
                                        <a href="{{ $recoltareSange->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span>
                                        </a>
                                        <div style="flex" class="">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeRecoltareSange{{ $recoltareSange->id }}"
                                                title="Șterge Recoltare Sânge"
                                                >
                                                <span class="badge bg-danger">Șterge</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- <div>Nu s-au gasit rezervări în baza de date. Încearcă alte date de căutare</div> --}}
                        @endforelse
                        </tbody>
                </table>
            </div>

                <nav>
                    <ul class="pagination justify-content-center">
                        {{$recoltariSange->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere recoltareSange --}}
    @foreach ($recoltariSange as $recoltareSange)
        <div class="modal fade text-dark" id="stergeRecoltareSange{{ $recoltareSange->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Recoltare sănge: <b>{{ $recoltareSange->cod }}</b></h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align:left;">
                    Ești sigur ca vrei să ștergi Recoltarea de Sânge?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                    <form method="POST" action="{{ $recoltareSange->path() }}">
                        @method('DELETE')
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger text-white"
                            >
                            Șterge Recoltarea
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
