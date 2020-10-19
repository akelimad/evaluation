<?php

namespace App\Console;

use App\Entretien;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendMentorEmail::class,
        Commands\CampaignEmailing::class,
        Commands\CampaignReminder::class,
        Commands\ClearExportExcelCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('export:clear')->cron('0 1 * * *'); // daily at 01h
        // $schedule->command('email:mentor')->hourly();

        $schedule->command('campaign')->cron('* * * * *'); // every minute

        $entretiens = Entretien::all();
        foreach ($entretiens as $e) {
            $schedule->command('campaign:reminder')->cron($e->getCronTabExpression());
        }
    }
}
