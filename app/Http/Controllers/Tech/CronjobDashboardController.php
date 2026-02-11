<?php

namespace App\Http\Controllers\Tech;

use App\Http\Controllers\Controller;
use App\Models\CronJobRun;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CronjobDashboardController extends Controller
{
    public function index(): View
    {
        $jobs = collect(config('cronjobs.jobs', []))
            ->map(function (array $config, string $jobKey) {
                $runs = CronJobRun::query()
                    ->where('job_key', $jobKey)
                    ->orderByDesc('started_at');

                $lastRun = (clone $runs)->first();
                $lastSuccess = (clone $runs)->where('status', 'success')->first();
                $lastFailure = (clone $runs)->where('status', 'failed')->first();

                return [
                    'job_key' => $jobKey,
                    'display_name' => $config['display_name'] ?? $jobKey,
                    'command' => $config['command'] ?? '',
                    'http_path' => $config['http_path'] ?? '',
                    'cron' => $config['cron'] ?? '',
                    'last_run' => $lastRun,
                    'last_success' => $lastSuccess,
                    'last_failure' => $lastFailure,
                    'runs_count' => (clone $runs)->count(),
                    'failed_count' => (clone $runs)->where('status', 'failed')->count(),
                ];
            })
            ->values();

        $recentRuns = CronJobRun::query()
            ->with('triggeredByUser:id,name,email')
            ->orderByDesc('started_at')
            ->paginate(30);

        $failedJobs = [];
        if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
            $failedJobs = DB::table('failed_jobs')
                ->select(['id', 'uuid', 'connection', 'queue', 'failed_at'])
                ->orderByDesc('failed_at')
                ->limit(50)
                ->get();
        }

        return view('tech.cronjobs.index', [
            'jobs' => $jobs,
            'recentRuns' => $recentRuns,
            'failedJobs' => $failedJobs,
        ]);
    }
}
