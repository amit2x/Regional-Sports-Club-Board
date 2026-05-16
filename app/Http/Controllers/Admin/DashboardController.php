<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Region;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function superAdminDashboard()
    {
        $stats = [
            'total_regions' => Region::count(),
            'total_airports' => Airport::count(),
            'total_employees' => Employee::where('employment_status', 'active')->count(),
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'published')
                ->where('end_date', '>=', now())
                ->count(),
            'total_registrations' => EventRegistration::count(),
            'pending_approvals' => EventRegistration::where('status', 'pending')->count(),
            'verified_documents' => DB::table('employee_documents')
                ->where('verification_status', 'verified')
                ->count(),
        ];

        // Region-wise statistics
        $regionStats = Region::withCount(['airports', 'events'])
            ->with(['events' => function($query) {
                $query->withCount('registrations');
            }])
            ->get();

        // Monthly registration trends
        $monthlyRegistrations = EventRegistration::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Gender distribution
        $genderStats = EventRegistration::select('employees.gender', DB::raw('count(*) as total'))
            ->join('employees', 'event_registrations.employee_id', '=', 'employees.id')
            ->groupBy('employees.gender')
            ->get();

        // Recent activities
        $recentActivities = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboards.super-admin', compact(
            'stats',
            'regionStats',
            'monthlyRegistrations',
            'genderStats',
            'recentActivities'
        ));
    }

    public function regionalDashboard()
    {
        $employee = auth()->guard('employee')->user();
        $region = Region::with('airports')->find($employee->region_id);

        if (!$region) {
            abort(403, 'You are not assigned to any region.');
        }

        $stats = [
            'total_airports' => $region->airports->count(),
            'total_employees' => Employee::where('region_id', $region->id)
                ->where('employment_status', 'active')
                ->count(),
            'total_events' => Event::where('region_id', $region->id)->count(),
            'active_events' => Event::where('region_id', $region->id)
                ->where('status', 'published')
                ->where('end_date', '>=', now())
                ->count(),
            'total_registrations' => EventRegistration::whereHas('event', function($q) use ($region) {
                $q->where('region_id', $region->id);
            })->count(),
            'pending_approvals' => EventRegistration::whereHas('event', function($q) use ($region) {
                $q->where('region_id', $region->id);
            })->where('status', 'pending')->count(),
        ];

        // Airport-wise participation
        $airportStats = Airport::where('region_id', $region->id)
            ->withCount(['employees', 'events'])
            ->with(['events' => function($query) {
                $query->withCount('registrations');
            }])
            ->get();

        // Recent events
        $recentEvents = Event::where('region_id', $region->id)
            ->withCount('registrations')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboards.regional', compact(
            'region',
            'stats',
            'airportStats',
            'recentEvents'
        ));
    }

    public function airportDashboard()
    {
        $employee = auth()->guard('employee')->user();
        $airport = Airport::find($employee->airport_id);

        if (!$airport) {
            abort(403, 'You are not assigned to any airport.');
        }

        $stats = [
            'total_employees' => Employee::where('airport_id', $airport->id)
                ->where('employment_status', 'active')
                ->count(),
            'total_events' => Event::where('airport_id', $airport->id)->count(),
            'active_events' => Event::where('airport_id', $airport->id)
                ->where('status', 'published')
                ->where('end_date', '>=', now())
                ->count(),
            'total_registrations' => EventRegistration::whereHas('event', function($q) use ($airport) {
                $q->where('airport_id', $airport->id);
            })->count(),
            'pending_verifications' => EventRegistration::whereHas('event', function($q) use ($airport) {
                $q->where('airport_id', $airport->id);
            })->where('status', 'pending')->count(),
        ];

        // Upcoming events
        $upcomingEvents = Event::where('airport_id', $airport->id)
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->withCount('registrations')
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Recent registrations
        $recentRegistrations = EventRegistration::whereHas('event', function($q) use ($airport) {
            $q->where('airport_id', $airport->id);
        })
        ->with(['employee', 'event'])
        ->latest()
        ->take(10)
        ->get();

        return view('admin.dashboards.airport', compact(
            'airport',
            'stats',
            'upcomingEvents',
            'recentRegistrations'
        ));
    }

    public function employeeDashboard()
    {
        $employee = auth()->guard('employee')->user();

        // Upcoming events
        $upcomingEvents = Event::where('status', 'published')
            ->where('end_date', '>=', now())
            ->where(function($query) use ($employee) {
                $query->whereNull('airport_id')
                    ->orWhere('airport_id', $employee->airport_id);
            })
            ->where(function($query) use ($employee) {
                $query->whereNull('region_id')
                    ->orWhere('region_id', $employee->region_id);
            })
            ->orderBy('start_date')
            ->take(6)
            ->get();

        // My registrations
        $myRegistrations = EventRegistration::where('employee_id', $employee->id)
            ->with('event')
            ->latest()
            ->take(10)
            ->get();

        // Statistics
        $stats = [
            'total_registrations' => $myRegistrations->count(),
            'approved_registrations' => $myRegistrations->where('status', 'approved')->count(),
            'pending_registrations' => $myRegistrations->where('status', 'pending')->count(),
            'rejected_registrations' => $myRegistrations->where('status', 'rejected')->count(),
        ];

        return view('employee.dashboard', compact(
            'employee',
            'upcomingEvents',
            'myRegistrations',
            'stats'
        ));
    }
}
