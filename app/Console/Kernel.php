<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        foreach (config('cronjobs.jobs', []) as $jobConfig) {
            if (empty($jobConfig['command']) || empty($jobConfig['cron'])) {
                continue;
            }

            $schedule->command($jobConfig['command'])
                ->cron($jobConfig['cron'])
                ->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
