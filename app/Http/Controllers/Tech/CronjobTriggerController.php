<?php

namespace App\Http\Controllers\Tech;

use App\Http\Controllers\Controller;
use App\Services\CronJobRunner;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CronjobTriggerController extends Controller
{
    public function trimiteEmail(string $key, CronJobRunner $runner): Response
    {
        return $this->run($key, 'trimite-email', $runner);
    }

    public function trimiteMementouriActivitatiCalendar(string $key, CronJobRunner $runner): Response
    {
        return $this->run($key, 'trimite-mementouri-activitati-calendar', $runner);
    }

    public function trimiteReminderDeciziiCas(string $key, CronJobRunner $runner): Response
    {
        return $this->run($key, 'trimite-reminder-decizii-cas', $runner);
    }

    public function trimiteReminderOferteInAsteptare(string $key, CronJobRunner $runner): Response
    {
        return $this->run($key, 'trimite-reminder-oferte-in-asteptare', $runner);
    }

    protected function run(string $providedKey, string $jobKey, CronJobRunner $runner): Response
    {
        $databaseKey = (string) DB::table('variabile')
            ->where('nume', 'cron_job_key')
            ->value('valoare');

        if ($databaseKey === '' || $providedKey !== $databaseKey) {
            return response('Cheia pentru Cron Joburi este incorecta!', 403);
        }

        $run = $runner->run($jobKey, 'http');
        $output = trim((string) $run->output);

        if ($run->status === 'failed') {
            $errorText = $run->error_message ?: 'Cron job failed.';
            $fullMessage = $output !== ''
                ? $errorText . PHP_EOL . PHP_EOL . $output
                : $errorText;

            return response($fullMessage, 500);
        }

        return response($output !== '' ? $output : 'Cron job executat cu succes.');
    }
}
