@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="mx-3 px-3 card container mx-auto" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-users me-1"></i>Utilizatori
                </span>
            </div>
            <div class="col-lg-6">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current()  }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-8">
                            <input type="text" class="form-control rounded-3" id="searchNume" name="searchNume" placeholder="Nume" value="{{ $searchNume }}">
                        </div>
                    </div>
                    <div class="row custom-search-form justify-content-center">
                        <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm btn-secondary text-white col-md-4 border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 text-end">
                {{-- <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="{{ url()->current() }}/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă Utilizator
                </a> --}}
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded-3">
                <table class="table table-striped table-hover">
                    <thead class="">
                        <tr class="" style="padding:2rem">
                            <th class="text-white culoare2">#</th>
                            <th class="text-white culoare2">Nume</th>
                            <th class="text-white culoare2">Rol</th>
                            <th class="text-white culoare2">Telefon</th>
                            <th class="text-white culoare2">Email</th>
                            {{-- <th class="text-white culoare2">Localitatea</th>
                            <th class="text-white culoare2 text-end">Acțiuni</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($useri as $user)
                            <tr>
                                <td align="">
                                    {{ ($useri ->currentpage()-1) * $useri ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    {{ $user->name }}
                                </td>
                                <td class="">
                                    @switch($user->role)
                                        @case(1)
                                            Vânzări
                                            @break
                                        @case(2)
                                            Comercial
                                            @break
                                        @case(3)
                                            Tehnic
                                            @break
                                        @default

                                    @endswitch
                                </td>
                                <td class="">
                                    {{ $user->telefon }}
                                </td>
                                <td class="">
                                    {{ $user->email }}
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                </table>
            </div>

                <nav>
                    <ul class="pagination justify-content-center">
                        {{$useri->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

@endsection
