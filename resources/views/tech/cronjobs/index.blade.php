@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card mx-auto" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-12">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-clock-rotate-left me-1"></i>Cronjobs
            </span>
        </div>
    </div>

    <div class="card-body px-3 py-3">
        @include ('errors')

        <div class="alert alert-info">
            Pentru executia automata, serverul trebuie sa ruleze <code>php artisan schedule:run</code> in fiecare minut.
        </div>

        <h5 class="mb-2">Configurare cronjobs</h5>
        <div class="table-responsive rounded-3 mb-4">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Job</th>
                        <th class="culoare2 text-white">Comanda</th>
                        <th class="culoare2 text-white">Cron</th>
                        <th class="culoare2 text-white">Ultima rulare</th>
                        <th class="culoare2 text-white">Ultima eroare</th>
                        <th class="culoare2 text-white">Rulari</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td>
                                <div><strong>{{ $job['display_name'] }}</strong></div>
                                <div><code>{{ $job['job_key'] }}</code></div>
                                <div class="small text-muted">{{ $job['http_path'] }}</div>
                            </td>
                            <td><code>{{ $job['command'] }}</code></td>
                            <td><code>{{ $job['cron'] }}</code></td>
                            <td>
                                @if ($job['last_run'])
                                    {{ $job['last_run']->started_at?->format('d.m.Y H:i:s') }}
                                    <div class="small">
                                        @if ($job['last_run']->status === 'success')
                                            <span class="text-success">success</span>
                                        @else
                                            <span class="text-danger">failed</span>
                                        @endif
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($job['last_failure'])
                                    {{ $job['last_failure']->started_at?->format('d.m.Y H:i:s') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $job['runs_count'] }} ({{ $job['failed_count'] }} fail)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h5 class="mb-2">Istoric rulari</h5>
        <div class="table-responsive rounded-3 mb-4">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">Start</th>
                        <th class="culoare2 text-white">Job</th>
                        <th class="culoare2 text-white">Sursa</th>
                        <th class="culoare2 text-white">Durata</th>
                        <th class="culoare2 text-white">Status</th>
                        <th class="culoare2 text-white">Trigger user</th>
                        <th class="culoare2 text-white">Detalii</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentRuns as $run)
                        <tr>
                            <td>{{ $run->started_at?->format('d.m.Y H:i:s') }}</td>
                            <td>
                                <div>{{ $run->display_name }}</div>
                                <div><code>{{ $run->job_key }}</code></div>
                            </td>
                            <td>{{ $run->source }}</td>
                            <td>{{ $run->duration_ms ? $run->duration_ms . ' ms' : '-' }}</td>
                            <td>
                                @if ($run->status === 'success')
                                    <span class="badge bg-success">success</span>
                                @elseif($run->status === 'failed')
                                    <span class="badge bg-danger">failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ $run->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($run->triggeredByUser)
                                    {{ $run->triggeredByUser->name }}<br>
                                    <small>{{ $run->triggeredByUser->email }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($run->error_message)
                                    <div class="text-danger">{{ $run->error_message }}</div>
                                @endif
                                @if ($run->output)
                                    <details>
                                        <summary>Output</summary>
                                        <pre class="mb-0">{{ $run->output }}</pre>
                                    </details>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nu exista rulari inregistrate.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $recentRuns->links() }}
            </ul>
        </nav>

        <h5 class="mb-2">failed_jobs (queue)</h5>
        <div class="table-responsive rounded-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="culoare2 text-white">ID</th>
                        <th class="culoare2 text-white">UUID</th>
                        <th class="culoare2 text-white">Connection</th>
                        <th class="culoare2 text-white">Queue</th>
                        <th class="culoare2 text-white">Failed at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($failedJobs as $failedJob)
                        <tr>
                            <td>{{ $failedJob->id }}</td>
                            <td><code>{{ $failedJob->uuid }}</code></td>
                            <td>{{ $failedJob->connection }}</td>
                            <td>{{ $failedJob->queue }}</td>
                            <td>{{ $failedJob->failed_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nu exista intrari in failed_jobs.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
