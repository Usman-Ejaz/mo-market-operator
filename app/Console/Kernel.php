<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->everyMinute();
        $schedule->command('audit:broken-links')
            ->timezone('Asia/Karachi')
            ->dailyAt('1:00');

        $schedule->command('flush:incomplete-clients')
            ->timezone('Asia/Karachi')
            ->dailyAt('1:00');

        $schedule->command('update-subscribers:rss-feed')
            ->timezone('Asia/Karachi')
            ->dailyAt('1:00');

        $schedule->command('toggle:publish-status')
            ->timezone('Asia/Karachi')
            ->everyFiveMinutes();
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
