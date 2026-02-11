<?php

namespace App\Services;

use App\Http\Controllers\CronJobController;
use App\Models\CronJobRun;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class CronJobRunner
{
    public function run(string $jobKey, string $source = 'scheduler', ?int $triggeredByUserId = null): CronJobRun
    {
        $jobConfig = config('cronjobs.jobs.' . $jobKey);

        if (!$jobConfig) {
            throw new InvalidArgumentException('Cron job necunoscut: ' . $jobKey);
        }

        $run = CronJobRun::create([
            'job_key' => $jobKey,
            'display_name' => $jobConfig['display_name'] ?? $jobKey,
            'source' => $source,
            'status' => 'running',
            'started_at' => now(),
            'triggered_by_user_id' => $triggeredByUserId,
        ]);

        $startedAt = microtime(true);
        $output = '';
        $errorMessage = null;
        $status = 'success';
        $outputBuffered = false;

        try {
            $cronJobKey = DB::table('variabile')
                ->where('nume', 'cron_job_key')
                ->value('valoare');

            if (!$cronJobKey) {
                throw new RuntimeException('Lipseste cheia "cron_job_key" in tabela variabile.');
            }

            ob_start();
            $outputBuffered = true;

            $controller = app(CronJobController::class);
            $this->executeJob($controller, $jobKey, (string) $cronJobKey);
            $output = trim((string) ob_get_clean());
            $outputBuffered = false;

            if (str_contains($output, 'Cheia pentru Cron Joburi este incorect')) {
                throw new RuntimeException($output);
            }

            if (str_contains($output, 'Nu toate emailurile sunt corecte')) {
                throw new RuntimeException($output);
            }
        } catch (Throwable $throwable) {
            if ($outputBuffered) {
                $output = trim((string) ob_get_clean());
            }

            $status = 'failed';
            $errorMessage = $throwable->getMessage();
        }

        $run->update([
            'status' => $status,
            'finished_at' => now(),
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            'output' => $output,
            'error_message' => $errorMessage,
        ]);

        return $run->fresh();
    }

    protected function executeJob(CronJobController $controller, string $jobKey, string $cronJobKey): void
    {
        switch ($jobKey) {
            case 'trimite-email':
                $controller->trimiteEmail($cronJobKey);
                break;

            case 'trimite-mementouri-activitati-calendar':
                $controller->trimiteMementouriActivitatiCalendar($cronJobKey);
                break;

            case 'trimite-reminder-decizii-cas':
                $controller->trimiteReminderDeciziiCas($cronJobKey);
                break;

            case 'trimite-reminder-oferte-in-asteptare':
                $controller->trimiteReminderOferteInAsteptare($cronJobKey);
                break;

            default:
                throw new InvalidArgumentException('Cron job necunoscut: ' . $jobKey);
        }
    }
}
