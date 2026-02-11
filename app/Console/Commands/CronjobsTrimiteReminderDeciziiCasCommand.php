<?php

namespace App\Console\Commands;

use App\Services\CronJobRunner;
use Illuminate\Console\Command;

class CronjobsTrimiteReminderDeciziiCasCommand extends Command
{
    protected $signature = 'cronjobs:trimite-reminder-decizii-cas';

    protected $description = 'Ruleaza cron job-ul pentru remindere decizii CAS.';

    public function handle(CronJobRunner $runner): int
    {
        $run = $runner->run('trimite-reminder-decizii-cas', 'scheduler');

        $this->line('Cron job run ID: ' . $run->id . ' | status: ' . $run->status);

        if ($run->output) {
            $this->newLine();
            $this->line($run->output);
        }

        if ($run->status === 'failed') {
            $this->error($run->error_message ?: 'Cron job failed.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
