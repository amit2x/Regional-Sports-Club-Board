<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Employee;
use App\Notifications\EventReminder;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    protected $signature = 'send:event-reminders';
    protected $description = 'Send reminders for upcoming events';

    public function handle()
    {
        // Remind 7 days before event
        $events7Days = Event::where('status', 'published')
            ->whereDate('start_date', now()->addDays(7))
            ->get();

        foreach ($events7Days as $event) {
            $this->sendReminders($event, 7);
        }

        // Remind 1 day before event
        $events1Day = Event::where('status', 'published')
            ->whereDate('start_date', now()->addDay())
            ->get();

        foreach ($events1Day as $event) {
            $this->sendReminders($event, 1);
        }

        // Remind about registration deadline (2 days before)
        $eventsRegistration = Event::where('status', 'published')
            ->whereDate('registration_last_date', now()->addDays(2))
            ->get();

        foreach ($eventsRegistration as $event) {
            $eligibleEmployees = app(\App\Services\EventEligibilityService::class)
                ->getEligibleEmployees($event);

            foreach ($eligibleEmployees as $employee) {
                $employee->notify(new EventReminder($event, 'registration closes in 2 days'));
            }
        }

        $this->info('Event reminders sent successfully.');
    }

    private function sendReminders($event, $days)
    {
        $registeredEmployees = $event->registrations()
            ->where('status', 'approved')
            ->with('employee')
            ->get()
            ->pluck('employee');

        foreach ($registeredEmployees as $employee) {
            $employee->notify(new EventReminder($event, $days));
        }
    }
}
