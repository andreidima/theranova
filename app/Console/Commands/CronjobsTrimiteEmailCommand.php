<?php

namespace App\Console\Commands;

use App\Services\CronJobRunner;
use Illuminate\Console\Command;

class CronjobsTrimiteEmailCommand extends Command
{
    protected $signature = 'cronjobs:trimite-email';

    protected $description = 'Ruleaza cron job-ul pentru trimitere email reminder protezare.';

    public function handle(CronJobRunner $runner): int
    {
        $run = $runner->run('trimite-email', 'scheduler');

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
