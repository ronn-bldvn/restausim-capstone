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
        // Register your custom commands here
        \App\Console\Commands\SendDeadlineReminders::class,
        \App\Console\Commands\SendDeadlineToday::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send reminders 1 day before deadline at 08:00 Manila time
        $schedule->command('send:deadline-reminders')
            ->dailyAt('08:00')
            ->timezone('Asia/Manila');

        // Send reminders on the day of the deadline at 08:05 Manila time
        $schedule->command('send:deadline-today')
            ->dailyAt('08:05')
            ->timezone('Asia/Manila');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Load all commands in app/Console/Commands
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
