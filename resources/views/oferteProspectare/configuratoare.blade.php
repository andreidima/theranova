@extends('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0 0;">
        <div class="col-lg-8">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-sliders me-1"></i>Configuratoare prospectare
            </span>
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include('errors')

        <form method="POST" action="{{ route('oferte-prospectare.configuratoare.store') }}" class="row align-items-end mb-4 px-3">
            @csrf
            <div class="col-lg-3">
                <label class="mb-0 ps-3">Denumire</label>
                <input name="denumire" class="form-control rounded-3" required>
            </div>
            <div class="col-lg-2">
                <label class="mb-0 ps-3">Categorie</label>
                <input name="categorie" class="form-control rounded-3" placeholder="Gamba / Coapsa">
            </div>
            <div class="col-lg-5">
                <label class="mb-0 ps-3">Text PDF</label>
                <input name="text_pdf" class="form-control rounded-3">
            </div>
            <div class="col-lg-1">
                <label class="mb-0 ps-3">Activ</label>
                <select name="activ" class="form-select rounded-3">
                    <option value="1">DA</option>
                    <option value="0">NU</option>
                </select>
            </div>
            <div class="col-lg-1">
                <button class="btn btn-success text-white rounded-3" type="submit">Adauga</button>
            </div>
        </form>

        @forelse($configuratoare as $configurator)
            <div class="border rounded-3 mx-3 mb-4 p-3">
                <form id="configurator-update-{{ $configurator->id }}" method="POST" action="{{ route('oferte-prospectare.configuratoare.update', $configurator) }}">
                    @csrf
                    @method('PATCH')
                </form>
                <div class="row align-items-end">
                    <div class="col-lg-3">
                        <label class="mb-0 ps-3">Denumire</label>
                        <input name="denumire" form="configurator-update-{{ $configurator->id }}" class="form-control rounded-3" value="{{ $configurator->denumire }}" required>
                    </div>
                    <div class="col-lg-2">
                        <label class="mb-0 ps-3">Categorie</label>
                        <input name="categorie" form="configurator-update-{{ $configurator->id }}" class="form-control rounded-3" value="{{ $configurator->categorie }}">
                    </div>
                    <div class="col-lg-4">
                        <label class="mb-0 ps-3">Text PDF</label>
                        <input name="text_pdf" form="configurator-update-{{ $configurator->id }}" class="form-control rounded-3" value="{{ $configurator->text_pdf }}">
                    </div>
                    <div class="col-lg-1">
                        <label class="mb-0 ps-3">Activ</label>
                        <select name="activ" form="configurator-update-{{ $configurator->id }}" class="form-select rounded-3">
                            <option value="1" {{ $configurator->activ ? 'selected' : '' }}>DA</option>
                            <option value="0" {{ !$configurator->activ ? 'selected' : '' }}>NU</option>
                        </select>
                    </div>
                    <div class="col-lg-2 text-end">
                        <button form="configurator-update-{{ $configurator->id }}" class="btn btn-sm btn-primary text-white rounded-3" type="submit">Salveaza</button>
                        <form method="POST" action="{{ route('oferte-prospectare.configuratoare.destroy', $configurator) }}" class="d-inline" onsubmit="return confirm('Sigur vrei sa dezactivezi acest configurator?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger text-white rounded-3" type="submit">Dezactiveaza</button>
                        </form>
                    </div>
                </div>

                <div class="mt-3">
                    <form method="POST" action="{{ route('oferte-prospectare.configuratoare.grupuri.store', $configurator) }}" class="row align-items-end">
                        @csrf
                        <div class="col-lg-5">
                            <label class="mb-0 ps-3">Grup nou</label>
                            <input name="denumire" class="form-control rounded-3" placeholder="Ex. Picior protetic" required>
                        </div>
                        <div class="col-lg-2">
                            <label class="mb-0 ps-3">Ordine</label>
                            <input name="ordine" type="number" min="0" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-sm btn-success text-white rounded-3" type="submit">Adauga grup</button>
                        </div>
                    </form>
                </div>

                @foreach($configurator->grupuri as $grup)
                    <div class="border rounded-3 p-2 mt-3 bg-light">
                        <form id="grup-update-{{ $grup->id }}" method="POST" action="{{ route('oferte-prospectare.configuratoare.grupuri.update', $grup) }}">
                            @csrf
                            @method('PATCH')
                        </form>
                        <div class="row align-items-end">
                            <div class="col-lg-5">
                                <label class="mb-0 ps-3">Grup</label>
                                <input name="denumire" form="grup-update-{{ $grup->id }}" class="form-control rounded-3" value="{{ $grup->denumire }}" required>
                            </div>
                            <div class="col-lg-2">
                                <label class="mb-0 ps-3">Ordine</label>
                                <input name="ordine" form="grup-update-{{ $grup->id }}" type="number" min="0" class="form-control rounded-3" value="{{ $grup->ordine }}">
                            </div>
                            <div class="col-lg-5 text-end">
                                <button form="grup-update-{{ $grup->id }}" class="btn btn-sm btn-primary text-white rounded-3" type="submit">Salveaza grup</button>
                                <form method="POST" action="{{ route('oferte-prospectare.configuratoare.grupuri.destroy', $grup) }}" class="d-inline" onsubmit="return confirm('Stergi grupul si componentele lui?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white rounded-3" type="submit">Sterge grup</button>
                                </form>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('oferte-prospectare.configuratoare.componente.store', $grup) }}" class="row align-items-end mt-2">
                            @csrf
                            <div class="col-lg-4">
                                <label class="mb-0 ps-3">Componenta</label>
                                <input name="denumire" class="form-control rounded-3" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="mb-0 ps-3">Producator</label>
                                <input name="producator" class="form-control rounded-3">
                            </div>
                            <div class="col-lg-2">
                                <label class="mb-0 ps-3">Pret</label>
                                <input name="pret" type="number" min="0" class="form-control rounded-3" value="0">
                            </div>
                            <div class="col-lg-1">
                                <label class="mb-0 ps-3">Ordine</label>
                                <input name="ordine" type="number" min="0" class="form-control rounded-3" value="0">
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-sm btn-success text-white rounded-3" type="submit">Adauga componenta</button>
                            </div>
                        </form>

                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Denumire</th>
                                        <th>Producator</th>
                                        <th>Pret</th>
                                        <th>Ordine</th>
                                        <th>Activ</th>
                                        <th class="text-end">Actiuni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grup->componente as $componenta)
                                        <tr>
                                            <td><input name="denumire" form="componenta-update-{{ $componenta->id }}" class="form-control rounded-3" value="{{ $componenta->denumire }}" required></td>
                                            <td><input name="producator" form="componenta-update-{{ $componenta->id }}" class="form-control rounded-3" value="{{ $componenta->producator }}"></td>
                                            <td><input name="pret" form="componenta-update-{{ $componenta->id }}" type="number" min="0" class="form-control rounded-3" value="{{ $componenta->pret }}"></td>
                                            <td><input name="ordine" form="componenta-update-{{ $componenta->id }}" type="number" min="0" class="form-control rounded-3" value="{{ $componenta->ordine }}"></td>
                                            <td>
                                                <select name="activ" form="componenta-update-{{ $componenta->id }}" class="form-select rounded-3">
                                                    <option value="1" {{ $componenta->activ ? 'selected' : '' }}>DA</option>
                                                    <option value="0" {{ !$componenta->activ ? 'selected' : '' }}>NU</option>
                                                </select>
                                            </td>
                                            <td class="text-end">
                                                <form id="componenta-update-{{ $componenta->id }}" method="POST" action="{{ route('oferte-prospectare.configuratoare.componente.update', $componenta) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-primary text-white rounded-3" type="submit">Salveaza</button>
                                                </form>
                                                <form method="POST" action="{{ route('oferte-prospectare.configuratoare.componente.destroy', $componenta) }}" class="d-inline" onsubmit="return confirm('Sigur vrei sa dezactivezi aceasta componenta?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger text-white rounded-3" type="submit">Dezactiveaza</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="text-center py-4">Nu exista configuratoare.</div>
        @endforelse

        <nav>
            <ul class="pagination justify-content-center">
                {{ $configuratoare->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
