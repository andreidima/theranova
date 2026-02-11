@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-3">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-user-secret me-1"></i>Impersonare utilizatori
            </span>
        </div>
        <div class="col-lg-6">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="row mb-1 custom-search-form justify-content-center">
                    <div class="col-lg-8">
                        <input type="text" class="form-control rounded-3" name="search" placeholder="Nume sau email" value="{{ $search }}">
                    </div>
                </div>
                <div class="row custom-search-form justify-content-center">
                    <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                        <i class="fas fa-search text-white me-1"></i>Cauta
                    </button>
                    <a class="btn btn-sm btn-secondary text-white col-md-4 border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                        <i class="far fa-trash-alt text-white me-1"></i>Reseteaza cautarea
                    </a>
                </div>
            </form>
        </div>
        <div class="col-lg-3 text-end">
            @if ($isImpersonating)
                <form method="POST" action="{{ route('tech.impersonare.stop') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning text-dark border border-dark rounded-3 col-md-8">
                        Opreste impersonarea
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include ('errors')

        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">#</th>
                        <th class="culoare2 text-white">Nume</th>
                        <th class="culoare2 text-white">Email</th>
                        <th class="culoare2 text-white">Rol</th>
                        <th class="culoare2 text-white text-end">Actiune</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ ($users->currentpage()-1) * $users->perpage() + $loop->index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case(1)
                                        Vanzari
                                        @break
                                    @case(2)
                                        Comercial
                                        @break
                                    @case(3)
                                        Tehnic
                                        @break
                                    @default
                                        -
                                @endswitch
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('tech.impersonare.start', $user) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger text-white border border-dark rounded-3">
                                        Impersoneaza
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nu exista utilizatori eligibili.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $users->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
