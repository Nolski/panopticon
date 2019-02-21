<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('sync:structure')->weekends();

         $schedule->command('sync:users')->dailyAt('16:00');

         $schedule->command('sync:data job-seeker')->dailyAt('17:00');

         $schedule->command('sync:data firm')->dailyAt('17:00');

         $schedule->command('sync:data job-opening')->dailyAt('17:03');

         $schedule->command('sync:data match')->dailyAt('17:06');

         $schedule->command('sync:match_scores')->dailyAt('17:20');

         $schedule->command('sync:activities job-seeker-monthly-followup')->dailyAt('17:10');

         $schedule->command('sync:activities firm-monthly-followup')->dailyAt('17:10');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
