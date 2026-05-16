<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\EventEligibilityService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eligibilityService;

    public function __construct(EventEligibilityService $eligibilityService)
    {
        $this->eligibilityService = $eligibilityService;
    }

    /**
     * List events API
     */
    public function index(Request $request)
    {
        $employee = $request->user();

        $query = Event::where('status', 'published')
            ->where('is_published', true)
            ->with(['region', 'airport']);

        // Filter by event type
        if ($request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        // Filter eligible events for employee
        if ($request->eligible_only) {
            $events = $query->get()->filter(function($event) use ($employee) {
                return $this->eligibilityService->isEligible($employee, $event);
            });
        } else {
            $events = $query->paginate($request->per_page ?? 15);
        }

        $eventsData = $events->map(function($event) use ($employee) {
            return [
                'id' => $event->id,
                'event_name' => $event->event_name,
                'event_code' => $event->event_code,
                'description' => $event->description,
                'banner_url' => $event->banner_image ? asset('storage/' . $event->banner_image) : null,
                'venue' => $event->venue,
                'region' => $event->region ? $event->region->name : null,
                'airport' => $event->airport ? $event->airport->name : null,
                'event_type' => $event->event_type,
                'participation_type' => $event->participation_type,
                'start_date' => $event->start_date->format('Y-m-d H:i:s'),
                'end_date' => $event->end_date->format('Y-m-d H:i:s'),
                'registration_last_date' => $event->registration_last_date->format('Y-m-d H:i:s'),
                'max_participants' => $event->max_participants,
                'registered_count' => $event->approved_count,
                'available_slots' => $event->availableSlots,
                'is_eligible' => $this->eligibilityService->isEligible($employee, $event),
                'is_registered' => $event->registrations()->where('employee_id', $employee->id)->exists(),
                'is_registration_open' => $event->isRegistrationOpen,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $eventsData,
            'pagination' => $events instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ] : null
        ]);
    }

    /**
     * Event details API
     */
    public function show($id, Request $request)
    {
        $event = Event::with(['region', 'airport', 'formTemplate'])->findOrFail($id);
        $employee = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $event->id,
                'event_name' => $event->event_name,
                'event_code' => $event->event_code,
                'description' => $event->description,
                'banner_url' => $event->banner_image ? asset('storage/' . $event->banner_image) : null,
                'venue' => $event->venue,
                'region' => $event->region ? $event->region->name : null,
                'airport' => $event->airport ? $event->airport->name : null,
                'event_type' => $event->event_type,
                'participation_type' => $event->participation_type,
                'start_date' => $event->start_date->format('Y-m-d H:i:s'),
                'end_date' => $event->end_date->format('Y-m-d H:i:s'),
                'registration_last_date' => $event->registration_last_date->format('Y-m-d H:i:s'),
                'max_participants' => $event->max_participants,
                'gender_eligibility' => $event->gender_eligibility,
                'min_age' => $event->min_age,
                'max_age' => $event->max_age,
                'department_eligibility' => $event->department_eligibility,
                'rules_regulations' => $event->rules_regulations,
                'required_documents' => $event->required_documents,
                'registered_count' => $event->approved_count,
                'available_slots' => $event->availableSlots,
                'is_eligible' => $this->eligibilityService->isEligible($employee, $event),
                'is_registered' => $event->registrations()->where('employee_id', $employee->id)->exists(),
                'is_registration_open' => $event->isRegistrationOpen,
                'form_fields' => $event->formTemplate ? $event->formTemplate->form_fields : [],
            ]
        ]);
    }

    /**
     * Register for event API
     */
    public function register($eventId, Request $request)
    {
        $employee = $request->user();
        $event = Event::findOrFail($eventId);

        // Check eligibility
        if (!$this->eligibilityService->isEligible($employee, $event)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not eligible for this event'
            ], 403);
        }

        // Check registration is open
        if (!$event->isRegistrationOpen) {
            return response()->json([
                'success' => false,
                'message' => 'Registration is closed'
            ], 403);
        }

        // Validate form data
        $validationRules = [];
        if ($event->formTemplate && $event->formTemplate->validation_rules) {
            $validationRules = $event->formTemplate->validation_rules;
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->form_data, $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create registration
        $registration = EventRegistration::create([
            'registration_number' => EventRegistration::generateRegistrationNumber(),
            'event_id' => $eventId,
            'employee_id' => $employee->id,
            'status' => $request->submit ? 'pending' : 'draft',
            'form_data' => $request->form_data,
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $docType => $file) {
                $filePath = $file->store(
                    "registrations/{$registration->id}/documents",
                    'public'
                );

                $registration->documents()->create([
                    'document_type' => $docType,
                    'document_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'verification_status' => 'pending',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $request->submit ? 'Registration submitted successfully' : 'Draft saved',
            'registration_id' => $registration->id,
            'registration_number' => $registration->registration_number,
        ]);
    }

    /**
     * My registrations API
     */
    public function myRegistrations(Request $request)
    {
        $employee = $request->user();

        $registrations = EventRegistration::with(['event'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate($request->per_page ?? 15);

        $data = $registrations->map(function($reg) {
            return [
                'id' => $reg->id,
                'registration_number' => $reg->registration_number,
                'event_name' => $reg->event->event_name ?? 'N/A',
                'event_code' => $reg->event->event_code ?? 'N/A',
                'event_date' => $reg->event ? $reg->event->start_date->format('d M Y') : 'N/A',
                'venue' => $reg->event->venue ?? 'N/A',
                'status' => $reg->status,
                'submitted_at' => $reg->created_at->format('Y-m-d H:i:s'),
                'documents_verified' => $reg->documents_verified,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
                'per_page' => $registrations->perPage(),
                'total' => $registrations->total(),
            ]
        ]);
    }
}
