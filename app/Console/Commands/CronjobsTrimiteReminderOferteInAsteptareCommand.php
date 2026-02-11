<?php

namespace App\Console\Commands;

use App\Services\CronJobRunner;
use Illuminate\Console\Command;

class CronjobsTrimiteReminderOferteInAsteptareCommand extends Command
{
    protected $signature = 'cronjobs:trimite-reminder-oferte-in-asteptare';

    protected $description = 'Ruleaza cron job-ul pentru remindere oferte in asteptare mai vechi de 3 luni.';

    public function handle(CronJobRunner $runner): int
    {
        $run = $runner->run('trimite-reminder-oferte-in-asteptare', 'scheduler');

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
