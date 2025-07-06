<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendAppointmentReminder as AppointmentReminderJob;

class SendAppointmentRemider extends Command
{
    protected $signature = 'appointment_reminder:send';

    protected $description = 'Send appointment reminders to patients before their appointments.';

    public function handle()
    {
        dispatch(new AppointmentReminderJob());
    
        return Command::SUCCESS;
    }
}
