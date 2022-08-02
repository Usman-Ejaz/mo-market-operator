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
        $schedule->command('audit:broken-links')
            ->timezone('Asia/Karachi')
            ->dailyAt('8:00');

        $schedule->command('flush:incomplete-clients')
            ->timezone('Asia/Karachi')
            ->dailyAt('00:00');     // at night 12AM.

        $schedule->command('toggle:publish-status')
            ->timezone('Asia/Karachi')
            ->everyFiveMinutes();

        $schedule->command('remove:downloaded-forms')
            ->timezone('Asia/Karachi')
            ->dailyAt('02:00');     // at night 2AM.
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
