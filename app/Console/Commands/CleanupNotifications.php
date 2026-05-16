<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class CleanupNotifications extends Command
{
    protected $signature = 'notifications:cleanup {--days=30 : Days to keep notifications}';
    protected $description = 'Clean up old notifications';

    public function handle()
    {
        $days = $this->option('days');

        $count = DatabaseNotification::where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Deleted {$count} old notifications.");
    }
}
