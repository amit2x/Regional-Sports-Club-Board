<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Send event reminders daily at 9 AM
Schedule::command('send:event-reminders')->dailyAt('09:00');

// Clean up old notifications (30 days)
Schedule::command('notifications:cleanup')->daily();

// Update event statuses
Schedule::command('events:update-status')->hourly();

// Generate daily reports (cache)
Schedule::command('reports:generate-daily')->dailyAt('23:59');
