<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateDailyReports extends Command
{
    protected $signature = 'reports:generate-daily';
    protected $description = 'Generate and cache daily reports';

    public function handle()
    {
        try {
            // Generate daily statistics
            $dailyStats = [
                'date' => now()->toDateString(),
                'new_registrations' => EventRegistration::whereDate('created_at', now())->count(),
                'approved_registrations' => EventRegistration::whereDate('approved_at', now())->count(),
                'new_events' => Event::whereDate('created_at', now())->count(),
                'published_events' => Event::whereDate('updated_at', now())
                    ->where('status', 'published')
                    ->count(),
                'active_employees' => Employee::where('employment_status', 'active')->count(),
                'total_registrations_today' => EventRegistration::whereDate('created_at', now())
                    ->where('status', '!=', 'draft')
                    ->count(),
            ];

            // Cache daily stats
            Cache::put('daily_stats_' . now()->toDateString(), $dailyStats, now()->addDays(7));

            // Update homepage cache
            Cache::forget('upcoming_events');
            Cache::forget('latest_announcements');
            Cache::forget('public_statistics');

            // Generate and store daily report file
            $reportPath = storage_path('app/reports/daily/');
            if (!is_dir($reportPath)) {
                mkdir($reportPath, 0755, true);
            }

            $filename = 'daily_report_' . now()->format('Y-m-d') . '.json';
            file_put_contents(
                $reportPath . $filename,
                json_encode($dailyStats, JSON_PRETTY_PRINT)
            );

            Log::info('Daily reports generated successfully', $dailyStats);
            $this->info('Daily reports generated successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to generate daily reports: ' . $e->getMessage());
            $this->error('Failed to generate daily reports: ' . $e->getMessage());
        }
    }
}
