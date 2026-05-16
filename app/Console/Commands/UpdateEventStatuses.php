<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-status';
    protected $description = 'Update event statuses based on dates';

    public function handle()
    {
        // Complete events that have passed end date
        $completedCount = Event::where('status', 'published')
            ->where('end_date', '<', now())
            ->update(['status' => 'completed']);

        $this->info("Updated {$completedCount} events to completed status.");

        // Close registrations for events past registration date
        $closedCount = Event::where('status', 'published')
            ->where('registration_last_date', '<', now())
            ->where('is_published', true)
            ->update(['is_published' => false]);

        $this->info("Closed registrations for {$closedCount} events.");
    }
}
