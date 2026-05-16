<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Employee;
use App\Models\Region;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipationReport;
use App\Exports\EventReport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:view_reports');
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        $user = auth()->guard('employee')->user();

        // Get filter parameters based on role
        if ($user->hasRole('super_admin')) {
            $regions = Region::where('status', 'active')->get();
            $airports = Airport::where('status', 'active')->get();
        } elseif ($user->hasRole('regional_sports_secretary')) {
            $regions = Region::where('id', $user->region_id)->get();
            $airports = Airport::where('region_id', $user->region_id)->get();
        } else {
            $regions = collect([]);
            $airports = Airport::where('id', $user->airport_id)->get();
        }

        return view('admin.reports.index', compact('regions', 'airports'));
    }

    /**
     * Generate participation report
     */
    public function participationReport(Request $request)
    {
        $request->validate([
            'region_id' => 'nullable|exists:regions,id',
            'airport_id' => 'nullable|exists:airports,id',
            'event_id' => 'nullable|exists:events,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'nullable|in:approved,pending,rejected',
        ]);

        $query = EventRegistration::with([
            'event',
            'employee.airport',
            'employee.region'
        ]);

        // Apply filters
        if ($request->region_id) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        if ($request->airport_id) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('airport_id', $request->airport_id);
            });
        }

        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->gender) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        // Role-based filtering
        $user = auth()->guard('employee')->user();
        if ($user->hasRole('regional_sports_secretary')) {
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('region_id', $user->region_id);
            });
        } elseif ($user->hasRole('airport_sports_secretary')) {
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('airport_id', $user->airport_id);
            });
        }

        $registrations = $query->latest()->get();

        // Calculate statistics
        $statistics = [
            'total' => $registrations->count(),
            'approved' => $registrations->where('status', 'approved')->count(),
            'pending' => $registrations->where('status', 'pending')->count(),
            'rejected' => $registrations->where('status', 'rejected')->count(),
            'male' => $registrations->where('employee.gender', 'male')->count(),
            'female' => $registrations->where('employee.gender', 'female')->count(),
            'unique_employees' => $registrations->unique('employee_id')->count(),
            'unique_events' => $registrations->unique('event_id')->count(),
        ];

        if ($request->export_type === 'excel') {
            return Excel::download(
                new ParticipationReport($registrations, $statistics),
                'participation_report_' . date('Y-m-d') . '.xlsx'
            );
        }

        if ($request->export_type === 'pdf') {
            $pdf = PDF::loadView('admin.reports.exports.participation-pdf', compact('registrations', 'statistics'));
            return $pdf->download('participation_report_' . date('Y-m-d') . '.pdf');
        }

        if ($request->ajax()) {
            return response()->json([
                'statistics' => $statistics,
                'html' => view('admin.reports.partials.participation-table', compact('registrations'))->render()
            ]);
        }

        return view('admin.reports.participation', compact('registrations', 'statistics'));
    }

    /**
     * Generate event-wise report
     */
    public function eventReport(Request $request)
    {
        $request->validate([
            'region_id' => 'nullable|exists:regions,id',
            'event_type' => 'nullable|in:regional,airport,inter_airport,annual_meet',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Event::with(['region', 'airport'])
            ->withCount('registrations')
            ->withCount(['registrations as approved_count' => function($q) {
                $q->where('status', 'approved');
            }]);

        // Apply filters
        if ($request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->date_from) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        // Role-based filtering
        $user = auth()->guard('employee')->user();
        if ($user->hasRole('regional_sports_secretary')) {
            $query->where('region_id', $user->region_id);
        } elseif ($user->hasRole('airport_sports_secretary')) {
            $query->where('airport_id', $user->airport_id);
        }

        $events = $query->latest('start_date')->get();

        $statistics = [
            'total_events' => $events->count(),
            'published_events' => $events->where('status', 'published')->count(),
            'completed_events' => $events->where('status', 'completed')->count(),
            'total_registrations' => $events->sum('registrations_count'),
            'total_approved' => $events->sum('approved_count'),
            'average_participation' => $events->count() > 0
                ? round($events->avg('registrations_count'), 2)
                : 0,
        ];

        if ($request->export_type === 'excel') {
            return Excel::download(
                new EventReport($events, $statistics),
                'event_report_' . date('Y-m-d') . '.xlsx'
            );
        }

        if ($request->export_type === 'pdf') {
            $pdf = PDF::loadView('admin.reports.exports.event-pdf', compact('events', 'statistics'));
            return $pdf->download('event_report_' . date('Y-m-d') . '.pdf');
        }

        if ($request->ajax()) {
            return response()->json([
                'statistics' => $statistics,
                'html' => view('admin.reports.partials.event-table', compact('events'))->render()
            ]);
        }

        return view('admin.reports.events', compact('events', 'statistics'));
    }

    /**
     * Generate region-wise analytics
     */
    public function regionAnalytics(Request $request)
    {
        $regions = Region::withCount(['airports', 'employees'])
            ->with(['events' => function($query) {
                $query->withCount('registrations');
            }])
            ->get();

        $analytics = $regions->map(function($region) {
            $regionEvents = $region->events;
            $totalRegistrations = $regionEvents->sum('registrations_count');
            $totalEvents = $regionEvents->count();

            return [
                'region_name' => $region->name,
                'region_code' => $region->code,
                'airports' => $region->airports_count,
                'employees' => $region->employees_count,
                'events' => $totalEvents,
                'registrations' => $totalRegistrations,
                'average_participation' => $totalEvents > 0
                    ? round($totalRegistrations / $totalEvents, 2)
                    : 0,
                'active_events' => $regionEvents->where('status', 'published')->count(),
                'completed_events' => $regionEvents->where('status', 'completed')->count(),
            ];
        });

        return view('admin.reports.region-analytics', compact('analytics'));
    }

    /**
     * Generate gender analytics
     */
    public function genderAnalytics(Request $request)
    {
        $genderStats = EventRegistration::join('employees', 'event_registrations.employee_id', '=', 'employees.id')
            ->select(
                'employees.gender',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN event_registrations.status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN event_registrations.status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->groupBy('employees.gender')
            ->get();

        // Monthly trend
        $monthlyTrend = EventRegistration::join('employees', 'event_registrations.employee_id', '=', 'employees.id')
            ->select(
                DB::raw('DATE_FORMAT(event_registrations.created_at, "%Y-%m") as month'),
                'employees.gender',
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('event_registrations.created_at', date('Y'))
            ->groupBy('month', 'gender')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        return view('admin.reports.gender-analytics', compact('genderStats', 'monthlyTrend'));
    }

    /**
     * Generate sports category analytics
     */
    public function sportsCategoryAnalytics(Request $request)
    {
        $categoryStats = Employee::select('sports_category', DB::raw('COUNT(*) as total'))
            ->whereNotNull('sports_category')
            ->where('employment_status', 'active')
            ->groupBy('sports_category')
            ->orderByDesc('total')
            ->get();

        // Participation by sports category
        $participationByCategory = EventRegistration::join('employees', 'event_registrations.employee_id', '=', 'employees.id')
            ->join('events', 'event_registrations.event_id', '=', 'events.id')
            ->select(
                'events.event_type',
                'employees.sports_category',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('employees.sports_category')
            ->groupBy('events.event_type', 'employees.sports_category')
            ->get()
            ->groupBy('event_type');

        return view('admin.reports.sports-category', compact('categoryStats', 'participationByCategory'));
    }

    /**
     * Generate employee participation history
     */
    public function employeeHistory(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        $registrations = EventRegistration::with('event')
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        $stats = [
            'total_participations' => $registrations->count(),
            'approved' => $registrations->where('status', 'approved')->count(),
            'events_won' => $registrations->where('result', 'won')->count(),
            'participation_years' => $registrations->groupBy(function($reg) {
                return $reg->created_at->format('Y');
            })->count(),
        ];

        if ($request->export_type === 'pdf') {
            $pdf = PDF::loadView('admin.reports.exports.employee-history-pdf', compact('employee', 'registrations', 'stats'));
            return $pdf->download($employee->employee_id . '_participation_history.pdf');
        }

        return view('admin.reports.employee-history', compact('employee', 'registrations', 'stats'));
    }

    /**
     * Dashboard analytics data (AJAX)
     */
    public function dashboardAnalytics(Request $request)
    {
        $period = $request->period ?? 'year';

        switch ($period) {
            case 'week':
                $startDate = now()->subWeek();
                break;
            case 'month':
                $startDate = now()->subMonth();
                break;
            case 'quarter':
                $startDate = now()->subQuarter();
                break;
            case 'year':
            default:
                $startDate = now()->subYear();
                break;
        }

        // Registration trend
        $registrationTrend = EventRegistration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Event type distribution
        $eventDistribution = Event::select('event_type', DB::raw('COUNT(*) as total'))
            ->groupBy('event_type')
            ->get();

        // Approval rate
        $approvalRate = EventRegistration::select(
                DB::raw('status'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->get();

        // Top performing regions
        $topRegions = Region::withCount(['events', 'employees'])
            ->with(['events' => function($query) {
                $query->withCount('registrations');
            }])
            ->get()
            ->map(function($region) {
                return [
                    'name' => $region->name,
                    'events' => $region->events_count,
                    'registrations' => $region->events->sum('registrations_count'),
                    'employees' => $region->employees_count,
                ];
            })
            ->sortByDesc('registrations')
            ->take(5)
            ->values();

        return response()->json([
            'registration_trend' => $registrationTrend,
            'event_distribution' => $eventDistribution,
            'approval_rate' => $approvalRate,
            'top_regions' => $topRegions,
        ]);
    }
}
