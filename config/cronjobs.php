<?php

return [
    'jobs' => [
        'trimite-email' => [
            'display_name' => 'Trimite email reminder protezare',
            'command' => 'cronjobs:trimite-email',
            'http_path' => '/cronjobs/trimite-email/{key}',
            'cron' => env('CRON_TRIMITE_EMAIL_CRON', '0 8 * * *'),
        ],
        'trimite-mementouri-activitati-calendar' => [
            'display_name' => 'Trimite mementouri activitati calendar',
            'command' => 'cronjobs:trimite-mementouri-activitati-calendar',
            'http_path' => '/cronjobs/trimite-mementouri-activitati-calendar/{key}',
            'cron' => env('CRON_MEMENTOURI_CALENDAR_CRON', '0 8 * * *'),
        ],
        'trimite-reminder-decizii-cas' => [
            'display_name' => 'Trimite remindere decizii CAS',
            'command' => 'cronjobs:trimite-reminder-decizii-cas',
            'http_path' => '/cronjobs/trimite-reminder-decizii-cas/{key}',
            'cron' => env('CRON_REMINDER_DECIZII_CAS_CRON', '15 8 * * *'),
        ],
    ],
];
