@extends('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-6">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-circle-info me-1"></i>Informații generale
            </span>
        </div>
        <div class="col-lg-6 text-end">
            <button class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-6" data-bs-toggle="modal" data-bs-target="#adaugaInformatiiGenerale">
                <i class="fas fa-plus-square text-white me-1"></i>Adaugă informație
            </button>
        </div>
    </div>

    <div class="card-body px-0 py-3">

        @include('errors')

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">#</th>
                        <th class="culoare2 text-white">Variabilă</th>
                        <th class="culoare2 text-white">Valoare</th>
                        <th class="culoare2 text-white text-end">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($informatii as $informatie)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-break">{{ $informatie->variabila }}</td>
                            <td class="text-break">{{ $informatie->valoare }}</td>
                            <td class="text-end">
                                <a href="#" class="d-inline-flex align-items-center text-decoration-none me-1" data-bs-toggle="modal" data-bs-target="#modificaInformatiiGenerale{{ $informatie->id }}">
                                    <span class="badge bg-primary">Modifică</span>
                                </a>
                                <a href="#" class="d-inline-flex align-items-center text-decoration-none" data-bs-toggle="modal" data-bs-target="#stergeInformatiiGenerale{{ $informatie->id }}">
                                    <span class="badge bg-danger">Șterge</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nu există informații salvate încă.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal adăugare --}}
<div class="modal fade text-dark" id="adaugaInformatiiGenerale" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('informatii-generale.store') }}">
                @csrf
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">Adaugă informație</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="variabila" class="form-label">Variabilă</label>
                        <input type="text" class="form-control" id="variabila" name="variabila" value="{{ old('variabila') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="valoare" class="form-label">Valoare</label>
                        <textarea class="form-control" id="valoare" name="valoare" rows="2">{{ old('valoare') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                    <button type="submit" class="btn btn-success text-white">Salvează</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modalele pentru modificare și ștergere --}}
@foreach ($informatii as $informatie)
    <div class="modal fade text-dark" id="modificaInformatiiGenerale{{ $informatie->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ $informatie->path() }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Modifică informație</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="variabila{{ $informatie->id }}" class="form-label">Variabilă</label>
                            <input type="text" class="form-control" id="variabila{{ $informatie->id }}" name="variabila" value="{{ old('variabila', $informatie->variabila) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="valoare{{ $informatie->id }}" class="form-label">Valoare</label>
                            <textarea class="form-control" id="valoare{{ $informatie->id }}" name="valoare" rows="2">{{ old('valoare', $informatie->valoare) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                        <button type="submit" class="btn btn-primary text-white">Salvează</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-dark" id="stergeInformatiiGenerale{{ $informatie->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Șterge informație</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ești sigur că vrei să ștergi informația „{{ $informatie->variabila }}”? 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                    <form method="POST" action="{{ $informatie->path() }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger text-white">Șterge</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
