<?php

namespace App\Listeners;

use App\Events\SessionBooked;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduleSessionReminder implements ShouldQueue
{
    public function handle(SessionBooked $event): void
    {
        // The actual reminders are handled by the scheduled command
        // This listener ensures the session is flagged for reminder processing
        $event->session->update(['reminder_sent' => false]);
    }
}
