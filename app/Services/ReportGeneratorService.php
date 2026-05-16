<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Employee;
use App\Models\Region;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipationReport;
use Illuminate\Support\Facades\Storage;

class ReportGeneratorService
{
    /**
     * Generate participation summary report
     */
    public function generateParticipationSummary($filters = [])
    {
        $query = EventRegistration::with(['event', 'employee']);

        // Apply filters
        if (!empty($filters['region_id'])) {
            $query->whereHas('employee', function($q) use ($filters) {
                $q->where('region_id', $filters['region_id']);
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->get();
    }

    /**
     * Generate event performance report
     */
    public function generateEventPerformance($eventId)
    {
        $event = Event::with(['registrations.employee'])->findOrFail($eventId);

        return [
            'event' => $event,
            'total_registrations' => $event->registrations->count(),
            'approved' => $event->registrations->where('status', 'approved')->count(),
            'pending' => $event->registrations->where('status', 'pending')->count(),
            'rejected' => $event->registrations->where('status', 'rejected')->count(),
            'gender_distribution' => $event->registrations
                ->groupBy('employee.gender')
                ->map->count(),
            'department_distribution' => $event->registrations
                ->groupBy('employee.department')
                ->map->count(),
        ];
    }

    /**
     * Generate PDF report
     */
    public function generatePDF($view, $data, $filename)
    {
        $pdf = PDF::loadView($view, $data);

        $path = 'reports/' . $filename . '_' . date('Y-m-d_His') . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Generate Excel report
     */
    public function generateExcel($exportClass, $filename)
    {
        return Excel::download(
            $exportClass,
            $filename . '_' . date('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Generate monthly report
     */
    public function generateMonthlyReport($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        return [
            'period' => date('F Y', strtotime($startDate)),
            'new_events' => Event::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_registrations' => EventRegistration::whereBetween('created_at', [$startDate, $endDate])->count(),
            'approved_registrations' => EventRegistration::where('status', 'approved')
                ->whereBetween('approved_at', [$startDate, $endDate])
                ->count(),
            'new_employees' => Employee::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_employees' => Employee::where('employment_status', 'active')->count(),
        ];
    }
}
