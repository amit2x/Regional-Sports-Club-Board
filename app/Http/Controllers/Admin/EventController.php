<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\FormTemplate;
use App\Models\Region;
use App\Models\Airport;
use App\Services\EventEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    protected $eligibilityService;

    public function __construct(EventEligibilityService $eligibilityService)
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:view_events');
        $this->eligibilityService = $eligibilityService;
    }

    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $events = Event::with(['region', 'airport', 'creator'])
                ->withCount('registrations')
                ->select('events.*');

            // Filter by role
            $user = auth()->guard('employee')->user();
            if ($user->hasRole('regional_sports_secretary')) {
                $events->where('region_id', $user->region_id);
            } elseif ($user->hasRole('airport_sports_secretary')) {
                $events->where('airport_id', $user->airport_id);
            }

            return DataTables::of($events)
                ->addColumn('banner', function ($event) {
                    if ($event->banner_image) {
                        return '<img src="' . asset('storage/' . $event->banner_image) . '"
                                alt="Banner" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">No Banner</span>';
                })
                ->addColumn('event_details', function ($event) {
                    return '<strong>' . $event->event_name . '</strong><br>
                            <small class="text-muted">' . $event->event_code . '</small><br>
                            <small>' . $event->venue . '</small>';
                })
                ->addColumn('dates', function ($event) {
                    return '<small><strong>Start:</strong> ' . $event->start_date->format('d M Y') . '<br>
                            <strong>End:</strong> ' . $event->end_date->format('d M Y') . '<br>
                            <strong>Registration:</strong> ' . $event->registration_last_date->format('d M Y') . '</small>';
                })
                ->addColumn('type_badge', function ($event) {
                    $colors = [
                        'regional' => 'primary',
                        'airport' => 'info',
                        'inter_airport' => 'warning',
                        'annual_meet' => 'success'
                    ];
                    $color = $colors[$event->event_type] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' .
                           ucwords(str_replace('_', ' ', $event->event_type)) . '</span>';
                })
                ->addColumn('status_badge', function ($event) {
                    $colors = [
                        'draft' => 'secondary',
                        'published' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'info'
                    ];
                    $color = $colors[$event->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($event->status) . '</span>';
                })
                ->addColumn('registrations_count', function ($event) {
                    $approved = $event->registrations()->where('status', 'approved')->count();
                    $total = $event->registrations_count;
                    return '<span class="badge bg-success">' . $approved . '</span> / ' .
                           ($event->max_participants ? '<span class="badge bg-info">' . $event->max_participants . '</span>' : 'Unlimited') .
                           '<br><small class="text-muted">Total: ' . $total . '</small>';
                })
                ->addColumn('action', function ($event) {
                    $actions = '<div class="btn-group">';

                    if (auth()->user()->can('view_event_participants')) {
                        $actions .= '<a href="' . route('admin.events.registrations', $event->id) . '"
                                    class="btn btn-sm btn-info" title="View Registrations">
                                    <i class="bi bi-people"></i></a>';
                    }

                    if (auth()->user()->can('edit_events')) {
                        $actions .= '<a href="' . route('admin.events.edit', $event->id) . '"
                                    class="btn btn-sm btn-primary" title="Edit">
                                    <i class="bi bi-pencil"></i></a>';
                    }

                    if (auth()->user()->can('publish_events') && $event->status === 'draft') {
                        $actions .= '<button class="btn btn-sm btn-success publish-event"
                                    data-id="' . $event->id . '"
                                    title="Publish Event">
                                    <i class="bi bi-check-circle"></i></button>';
                    }

                    if (auth()->user()->can('delete_events')) {
                        $actions .= '<button class="btn btn-sm btn-danger delete-event"
                                    data-id="' . $event->id . '"
                                    title="Delete">
                                    <i class="bi bi-trash"></i></button>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['banner', 'event_details', 'dates', 'type_badge', 'status_badge', 'registrations_count', 'action'])
                ->make(true);
        }

        $statistics = [
            'total' => Event::count(),
            'published' => Event::where('status', 'published')->count(),
            'upcoming' => Event::where('start_date', '>=', now())->count(),
            'ongoing' => Event::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'completed' => Event::where('status', 'completed')->count(),
        ];

        return view('admin.events.index', compact('statistics'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $this->authorize('create_events');

        $regions = Region::where('status', 'active')->get();
        $airports = Airport::where('status', 'active')->get();
        $formTemplates = FormTemplate::where('is_active', true)->get();

        return view('admin.events.create', compact('regions', 'airports', 'formTemplates'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $this->authorize('create_events');

        $validator = Validator::make($request->all(), [
            'event_name' => 'required|string|max:255',
            'event_code' => 'required|string|unique:events,event_code',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'venue' => 'required|string|max:255',
            'region_id' => 'required_if:event_type,regional|exists:regions,id',
            'airport_id' => 'required_if:event_type,airport,inter_airport|exists:airports,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'registration_last_date' => 'required|date|before:start_date',
            'event_type' => 'required|in:regional,airport,inter_airport,annual_meet',
            'participation_type' => 'required|in:team,individual,both',
            'max_participants' => 'nullable|integer|min:1',
            'gender_eligibility' => 'required|in:male,female,other,all',
            'min_age' => 'nullable|integer|min:18',
            'max_age' => 'nullable|integer|gt:min_age',
            'department_eligibility' => 'nullable|array',
            'rules_regulations' => 'nullable|string',
            'required_documents' => 'nullable|array',
            'form_template_id' => 'nullable|exists:form_templates,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $eventData = $request->except(['banner_image', '_token']);
            $eventData['created_by'] = auth()->guard('employee')->id();
            $eventData['status'] = 'draft';
            $eventData['is_published'] = false;

            // Set region for airport events
            if ($request->event_type === 'airport' && $request->airport_id) {
                $airport = Airport::find($request->airport_id);
                $eventData['region_id'] = $airport->region_id;
            }

            // Handle banner upload
            if ($request->hasFile('banner_image')) {
                $eventData['banner_image'] = $request->file('banner_image')
                    ->store('event-banners', 'public');
            }

            // Convert arrays to JSON
            if ($request->has('department_eligibility')) {
                $eventData['department_eligibility'] = $request->department_eligibility;
            }
            if ($request->has('required_documents')) {
                $eventData['required_documents'] = $request->required_documents;
            }

            $event = Event::create($eventData);

            // Log activity
            activity()
                ->performedOn($event)
                ->causedBy(auth()->guard('employee')->user())
                ->withProperties(['event_code' => $event->event_code])
                ->log('created event');

            DB::commit();

            return redirect()->route('admin.events.index')
                ->with('success', 'Event created successfully. You can now add a registration form and publish it.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($eventData['banner_image'])) {
                Storage::disk('public')->delete($eventData['banner_image']);
            }

            return redirect()->back()
                ->with('error', 'Failed to create event: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified event
     */
    public function show($id)
    {
        $event = Event::with([
            'region',
            'airport',
            'creator',
            'formTemplate',
            'registrations' => function($query) {
                $query->with(['employee', 'documents'])
                      ->latest()
                      ->take(20);
            }
        ])->findOrFail($id);

        $statistics = [
            'total_registrations' => $event->registrations()->count(),
            'approved' => $event->registrations()->where('status', 'approved')->count(),
            'pending' => $event->registrations()->where('status', 'pending')->count(),
            'rejected' => $event->registrations()->where('status', 'rejected')->count(),
            'draft' => $event->registrations()->where('status', 'draft')->count(),
            'documents_verified' => $event->registrations()->where('documents_verified', true)->count(),
        ];

        // Gender distribution
        $genderDistribution = $event->registrations()
            ->join('employees', 'event_registrations.employee_id', '=', 'employees.id')
            ->select('employees.gender', DB::raw('count(*) as total'))
            ->groupBy('employees.gender')
            ->get();

        return view('admin.events.show', compact('event', 'statistics', 'genderDistribution'));
    }

    /**
     * Publish event
     */
    public function publish($id)
    {
        $this->authorize('publish_events');

        $event = Event::findOrFail($id);

        if ($event->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft events can be published.'
            ], 422);
        }

        // Validate event has required fields
        if (!$event->form_template_id) {
            return response()->json([
                'success' => false,
                'message' => 'Please add a registration form before publishing.'
            ], 422);
        }

        $event->status = 'published';
        $event->is_published = true;
        $event->save();

        // Send notifications to eligible employees
        $eligibleEmployees = $this->eligibilityService->getEligibleEmployees($event);
        // Notification logic here

        activity()
            ->performedOn($event)
            ->causedBy(auth()->guard('employee')->user())
            ->log('published event');

        return response()->json([
            'success' => true,
            'message' => 'Event published successfully.'
        ]);
    }

    /**
     * Cancel event
     */
    public function cancel(Request $request, $id)
    {
        $this->authorize('edit_events');

        $event = Event::findOrFail($id);

        if (!in_array($event->status, ['draft', 'published'])) {
            return response()->json([
                'success' => false,
                'message' => 'This event cannot be cancelled.'
            ], 422);
        }

        $event->status = 'cancelled';
        $event->is_published = false;
        $event->metadata = array_merge($event->metadata ?? [], [
            'cancellation_reason' => $request->reason,
            'cancelled_at' => now()->toDateTimeString(),
            'cancelled_by' => auth()->guard('employee')->id()
        ]);
        $event->save();

        activity()
            ->performedOn($event)
            ->causedBy(auth()->guard('employee')->user())
            ->log('cancelled event');

        return response()->json([
            'success' => true,
            'message' => 'Event cancelled successfully.'
        ]);
    }
}
