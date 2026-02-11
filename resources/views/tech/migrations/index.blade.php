@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-6">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-database me-1"></i>Migrations
            </span>
        </div>
        <div class="col-lg-6 text-end">
            <form method="POST" action="{{ route('tech.migrations.run-pending') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning text-dark border border-dark rounded-3">
                    Ruleaza migrari pending
                </button>
            </form>
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="border rounded-3 p-3 bg-light">
                    <div><strong>Conexiune DB:</strong> {{ $dbDefaultConnection }}</div>
                    <div><strong>Baza de date:</strong> {{ $dbName }}</div>
                    <div><strong>Tabele:</strong> {{ $databaseSummary['table_count'] }}</div>
                    <div><strong>Coloane:</strong> {{ $databaseSummary['column_count'] }}</div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="border rounded-3 p-3 bg-light">
                    <div><strong>Total fisiere migrari:</strong> {{ $migrationFiles->count() }}</div>
                    <div><strong>Rulate:</strong> {{ $ranMigrations->count() }}</div>
                    <div><strong>Pending:</strong> {{ $pendingMigrations->count() }}</div>
                </div>
            </div>
        </div>

        @if (!empty($lastMigrateOutput))
            <div class="mb-3">
                <div class="alert {{ $lastMigrateStatus === 'failed' ? 'alert-danger' : 'alert-info' }} mb-2">
                    Ultimul output pentru comanda <code>php artisan migrate</code>.
                </div>
                <pre class="bg-dark text-white p-3 rounded-3 mb-0" style="max-height: 320px; overflow: auto;">{{ $lastMigrateOutput }}</pre>
            </div>
        @endif

        <h5 class="mb-2">Status migrari</h5>
        <div class="table-responsive rounded-3 mb-4">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Migrare</th>
                        <th class="culoare2 text-white">Batch</th>
                        <th class="culoare2 text-white">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($migrationFiles as $migrationName)
                        @php
                            $ran = $ranMigrations->get($migrationName);
                        @endphp
                        <tr>
                            <td><code>{{ $migrationName }}</code></td>
                            <td>{{ $ran->batch ?? '-' }}</td>
                            <td>
                                @if ($ran)
                                    <span class="badge bg-success">Rulata</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h5 class="mb-2">Tabele DB</h5>
        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Nume</th>
                        <th class="culoare2 text-white">Engine</th>
                        <th class="culoare2 text-white">Rows</th>
                        <th class="culoare2 text-white">Collation</th>
                        <th class="culoare2 text-white">Ultima actualizare</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tableStatus as $table)
                        <tr>
                            <td><code>{{ $table['name'] }}</code></td>
                            <td>{{ $table['engine'] }}</td>
                            <td>{{ $table['rows'] }}</td>
                            <td>{{ $table['collation'] }}</td>
                            <td>{{ $table['updated_at'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nu am putut citi metadata tabelelor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
