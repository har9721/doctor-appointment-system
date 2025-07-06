<?php

namespace App\Console;

use App\Console\Commands\SendAppointmentRemider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendAppointmentRemider::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('appointment_reminder:send')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
