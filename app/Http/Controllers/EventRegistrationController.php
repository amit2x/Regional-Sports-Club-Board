<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\RegistrationDocument;
use App\Services\EventEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EventRegistrationController extends Controller
{
    protected $eligibilityService;

    public function __construct(EventEligibilityService $eligibilityService)
    {
        $this->middleware('auth:employee');
        $this->eligibilityService = $eligibilityService;
    }

    /**
     * Display available events for employee
     */
    public function availableEvents(Request $request)
    {
        $employee = auth()->guard('employee')->user();

        $events = Event::where('status', 'published')
            ->where('is_published', true)
            ->where('registration_last_date', '>=', now())
            ->where('start_date', '>=', now())
            ->with(['region', 'airport', 'formTemplate'])
            ->withCount(['registrations as approved_count' => function($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        // Filter eligible events
        $eligibleEvents = $events->filter(function($event) use ($employee) {
            return $this->eligibilityService->isEligible($employee, $event);
        });

        // Get employee's existing registrations
        $registeredEventIds = EventRegistration::where('employee_id', $employee->id)
            ->pluck('event_id')
            ->toArray();

        return view('employee.events.available', compact('eligibleEvents', 'registeredEventIds'));
    }

    /**
     * Show event details and registration form
     */
    public function showRegistrationForm($eventId)
    {
        $employee = auth()->guard('employee')->user();
        $event = Event::with('formTemplate')->findOrFail($eventId);

        // Check eligibility
        $eligibility = $this->eligibilityService->getEligibilityDetails($employee, $event);

        if (!$eligibility['eligible']) {
            return redirect()->back()
                ->with('error', 'You are not eligible for this event. ' . implode('. ', $eligibility['reasons']));
        }

        // Check if already registered
        $existingRegistration = EventRegistration::where('employee_id', $employee->id)
            ->where('event_id', $eventId)
            ->first();

        if ($existingRegistration && $existingRegistration->status !== 'withdrawn') {
            return redirect()->route('employee.registrations.show', $existingRegistration->id)
                ->with('info', 'You have already registered for this event.');
        }

        // Check if registration is still open
        if (!$event->isRegistrationOpen) {
            return redirect()->back()
                ->with('error', 'Registration is closed for this event.');
        }

        // Check available slots
        if ($event->max_participants && $event->availableSlots <= 0) {
            return redirect()->back()
                ->with('error', 'All slots are filled for this event.');
        }

        // Get form fields
        $formFields = $event->formTemplate ? $event->formTemplate->form_fields : [];

        // Get required documents
        $requiredDocuments = $event->required_documents ?? [];

        return view('employee.events.register', compact(
            'event',
            'employee',
            'formFields',
            'requiredDocuments',
            'existingRegistration'
        ));
    }

    /**
     * Submit registration
     */
    public function submitRegistration(Request $request, $eventId)
    {
        $employee = auth()->guard('employee')->user();
        $event = Event::with('formTemplate')->findOrFail($eventId);

        // Validate eligibility
        if (!$this->eligibilityService->isEligible($employee, $event)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not eligible for this event.'
            ], 403);
        }

        // Check registration deadline
        if (!$event->isRegistrationOpen) {
            return response()->json([
                'success' => false,
                'message' => 'Registration is closed.'
            ], 403);
        }

        // Validate form data based on template validation rules
        $validationRules = [];
        if ($event->formTemplate && $event->formTemplate->validation_rules) {
            $validationRules = $event->formTemplate->validation_rules;
        }

        // Add document validation
        if ($event->required_documents) {
            foreach ($event->required_documents as $docType) {
                $validationRules["documents.{$docType}"] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            }
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Check for existing draft
            $registration = EventRegistration::where('employee_id', $employee->id)
                ->where('event_id', $eventId)
                ->where('status', 'draft')
                ->first();

            if (!$registration) {
                // Create new registration
                $registration = EventRegistration::create([
                    'registration_number' => EventRegistration::generateRegistrationNumber(),
                    'event_id' => $eventId,
                    'employee_id' => $employee->id,
                    'status' => 'draft',
                    'form_data' => [],
                ]);
            }

            // Save form data
            $formData = $request->except(['documents', '_token']);
            $registration->form_data = $formData;

            // If submitting (not saving draft)
            if ($request->input('submit_type') === 'submit') {
                $registration->status = 'pending';
            }

            $registration->save();

            // Upload documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $docType => $file) {
                    // Delete existing document of same type
                    $existingDoc = $registration->documents()
                        ->where('document_type', $docType)
                        ->first();

                    if ($existingDoc) {
                        Storage::disk('public')->delete($existingDoc->file_path);
                        $existingDoc->delete();
                    }

                    // Store new document
                    $filePath = $file->store(
                        "registrations/{$registration->id}/documents",
                        'public'
                    );

                    RegistrationDocument::create([
                        'event_registration_id' => $registration->id,
                        'document_type' => $docType,
                        'document_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'verification_status' => 'pending',
                    ]);
                }
            }

            // Update documents verification status
            if ($request->input('submit_type') === 'submit') {
                $registration->documents_verified = false;
                $registration->save();
            }

            // Log activity
            activity()
                ->performedOn($registration)
                ->causedBy($employee)
                ->withProperties([
                    'event_id' => $eventId,
                    'action' => $request->input('submit_type')
                ])
                ->log($request->input('submit_type') === 'submit' ? 'submitted registration' : 'saved draft');

            DB::commit();

            $message = $request->input('submit_type') === 'submit'
                ? 'Registration submitted successfully. It will be reviewed by the authorities.'
                : 'Registration draft saved successfully.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'registration_id' => $registration->id,
                'redirect_url' => route('employee.registrations.show', $registration->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View my registrations
     */
    public function myRegistrations(Request $request)
    {
        $employee = auth()->guard('employee')->user();

        if ($request->ajax()) {
            $registrations = EventRegistration::with(['event'])
                ->where('employee_id', $employee->id)
                ->latest();

            return DataTables::of($registrations)
                ->addColumn('event_name', function($reg) {
                    return $reg->event ? $reg->event->event_name : 'N/A';
                })
                ->addColumn('event_dates', function($reg) {
                    if (!$reg->event) return 'N/A';
                    return $reg->event->start_date->format('d M Y') . ' - ' .
                           $reg->event->end_date->format('d M Y');
                })
                ->addColumn('status_badge', function($reg) {
                    $colors = [
                        'draft' => 'secondary',
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'withdrawn' => 'info'
                    ];
                    $color = $colors[$reg->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($reg->status) . '</span>';
                })
                ->addColumn('documents_status', function($reg) {
                    if ($reg->documents_verified) {
                        return '<span class="badge bg-success">Verified</span>';
                    }
                    $pendingDocs = $reg->documents()->where('verification_status', 'pending')->count();
                    return $pendingDocs > 0
                        ? '<span class="badge bg-warning">' . $pendingDocs . ' Pending</span>'
                        : '<span class="badge bg-info">No Documents</span>';
                })
                ->addColumn('action', function($reg) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('employee.registrations.show', $reg->id) . '"
                                class="btn btn-sm btn-info" title="View">
                                <i class="bi bi-eye"></i></a>';

                    if ($reg->status === 'draft') {
                        $actions .= '<a href="' . route('employee.events.register', $reg->event_id) . '"
                                    class="btn btn-sm btn-primary" title="Continue Registration">
                                    <i class="bi bi-pencil"></i></a>';
                    }

                    if (in_array($reg->status, ['draft', 'pending'])) {
                        $actions .= '<button class="btn btn-sm btn-danger withdraw-registration"
                                    data-id="' . $reg->id . '"
                                    title="Withdraw">
                                    <i class="bi bi-x-circle"></i></button>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['status_badge', 'documents_status', 'action'])
                ->make(true);
        }

        $stats = [
            'total' => EventRegistration::where('employee_id', $employee->id)->count(),
            'draft' => EventRegistration::where('employee_id', $employee->id)->where('status', 'draft')->count(),
            'pending' => EventRegistration::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'approved' => EventRegistration::where('employee_id', $employee->id)->where('status', 'approved')->count(),
            'rejected' => EventRegistration::where('employee_id', $employee->id)->where('status', 'rejected')->count(),
        ];

        return view('employee.registrations.index', compact('stats'));
    }

    /**
     * Show registration details
     */
    public function showRegistration($id)
    {
        $registration = EventRegistration::with([
            'event',
            'employee',
            'documents',
            'approver',
            'rejector'
        ])->findOrFail($id);

        // Authorization check
        $employee = auth()->guard('employee')->user();
        if ($employee->id !== $registration->employee_id &&
            !$employee->hasRole(['super_admin', 'regional_sports_secretary', 'airport_sports_secretary'])) {
            abort(403);
        }

        return view('employee.registrations.show', compact('registration'));
    }

    /**
     * Withdraw registration
     */
    public function withdrawRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);

        // Check if employee owns this registration
        if ($registration->employee_id !== auth()->guard('employee')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Can only withdraw draft or pending registrations
        if (!in_array($registration->status, ['draft', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'This registration cannot be withdrawn.'
            ], 422);
        }

        $registration->status = 'withdrawn';
        $registration->save();

        activity()
            ->performedOn($registration)
            ->causedBy(auth()->guard('employee')->user())
            ->log('withdrew registration');

        return response()->json([
            'success' => true,
            'message' => 'Registration withdrawn successfully.'
        ]);
    }
}
