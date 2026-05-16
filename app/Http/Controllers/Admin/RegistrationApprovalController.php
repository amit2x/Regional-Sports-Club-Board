<?php
// app/Http/Controllers/Admin/RegistrationApprovalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RegistrationApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:view_registrations');
    }

    /**
     * Display a listing of all registrations
     */
    public function index(Request $request)
    {
        $user = auth()->guard('employee')->user();

        if ($request->ajax()) {
            $registrations = EventRegistration::with(['event', 'employee', 'documents'])
                ->whereHas('event', function($query) use ($user) {
                    if ($user->hasRole('regional_sports_secretary')) {
                        $query->where('region_id', $user->region_id);
                    } elseif ($user->hasRole('airport_sports_secretary')) {
                        $query->where('airport_id', $user->airport_id);
                    }
                });

            return DataTables::of($registrations)
                ->addColumn('employee_details', function($reg) {
                    return '<strong>' . $reg->employee->name . '</strong><br>
                            <small>' . $reg->employee->employee_id . '</small>';
                })
                ->addColumn('event_details', function($reg) {
                    return '<strong>' . $reg->event->event_name . '</strong><br>
                            <small class="text-muted">' . $reg->event->event_code . '</small>';
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
                ->addColumn('action', function($reg) {
                    $actions = '<div class="btn-group">';

                    $actions .= '<a href="' . route('admin.registrations.show', $reg->id) . '"
                                class="btn btn-sm btn-info" title="View Details">
                                <i class="bi bi-eye"></i></a>';

                    if ($reg->status === 'pending') {
                        if (auth()->user()->can('approve_registrations')) {
                            $actions .= '<button class="btn btn-sm btn-success approve-registration"
                                        data-id="' . $reg->id . '" title="Approve">
                                        <i class="bi bi-check-lg"></i></button>';
                        }
                        if (auth()->user()->can('reject_registrations')) {
                            $actions .= '<button class="btn btn-sm btn-danger reject-registration"
                                        data-id="' . $reg->id . '" title="Reject">
                                        <i class="bi bi-x-lg"></i></button>';
                        }
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['employee_details', 'event_details', 'status_badge', 'action'])
                ->make(true);
        }

        $stats = [
            'total' => EventRegistration::count(),
            'pending' => EventRegistration::where('status', 'pending')->count(),
            'approved' => EventRegistration::where('status', 'approved')->count(),
            'rejected' => EventRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.registrations.index', compact('stats'));
    }

    /**
     * Display pending registrations for approval
     */
    public function pendingApprovals(Request $request)
    {
        $user = auth()->guard('employee')->user();

        if ($request->ajax()) {
            $registrations = EventRegistration::with(['event', 'employee', 'documents'])
                ->where('status', 'pending')
                ->whereHas('event', function($query) use ($user) {
                    if ($user->hasRole('regional_sports_secretary')) {
                        $query->where('region_id', $user->region_id);
                    } elseif ($user->hasRole('airport_sports_secretary')) {
                        $query->where('airport_id', $user->airport_id);
                    }
                });

            return DataTables::of($registrations)
                ->addColumn('employee_details', function($reg) {
                    return '<strong>' . $reg->employee->name . '</strong><br>
                            <small>' . $reg->employee->employee_id . '</small><br>
                            <small class="text-muted">' . $reg->employee->designation . '</small>';
                })
                ->addColumn('event_details', function($reg) {
                    return '<strong>' . $reg->event->event_name . '</strong><br>
                            <small class="text-muted">' . $reg->event->event_code . '</small>';
                })
                ->addColumn('submission_date', function($reg) {
                    return $reg->created_at->format('d M Y H:i');
                })
                ->addColumn('documents', function($reg) {
                    $docs = $reg->documents;
                    $html = '';
                    foreach ($docs as $doc) {
                        $color = $doc->verification_status === 'verified' ? 'success' :
                                ($doc->verification_status === 'rejected' ? 'danger' : 'warning');
                        $html .= '<span class="badge bg-' . $color . ' me-1">' .
                                ucfirst(str_replace('_', ' ', $doc->document_type)) .
                                '</span>';
                    }
                    return $html;
                })
                ->addColumn('action', function($reg) {
                    $actions = '<div class="btn-group">';

                    $actions .= '<a href="' . route('admin.registrations.show', $reg->id) . '"
                                class="btn btn-sm btn-info" title="View Details">
                                <i class="bi bi-eye"></i></a>';

                    if (auth()->user()->can('approve_registrations')) {
                        $actions .= '<button class="btn btn-sm btn-success approve-registration"
                                    data-id="' . $reg->id . '" title="Approve">
                                    <i class="bi bi-check-lg"></i></button>';
                    }

                    if (auth()->user()->can('reject_registrations')) {
                        $actions .= '<button class="btn btn-sm btn-danger reject-registration"
                                    data-id="' . $reg->id . '" title="Reject">
                                    <i class="bi bi-x-lg"></i></button>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['employee_details', 'event_details', 'documents', 'action'])
                ->make(true);
        }

        $pendingCount = EventRegistration::where('status', 'pending')->count();

        return view('admin.registrations.pending', compact('pendingCount'));
    }

    /**
     * Show registration details for approval
     */
    public function showRegistration($id)
    {
        $registration = EventRegistration::with([
            'event.formTemplate',
            'employee.airport',
            'employee.region',
            'documents'
        ])->findOrFail($id);

        // Check authorization
        $user = auth()->guard('employee')->user();
        if ($user->hasRole('regional_sports_secretary') &&
            $registration->event->region_id !== $user->region_id) {
            abort(403);
        }
        if ($user->hasRole('airport_sports_secretary') &&
            $registration->event->airport_id !== $user->airport_id) {
            abort(403);
        }

        $approvalHistory = $registration->approvalHistory ?? [];

        return view('admin.registrations.show', compact('registration', 'approvalHistory'));
    }

    /**
     * Approve registration
     */
    public function approve(Request $request, $id)
    {
        $this->authorize('approve_registrations');

        $registration = EventRegistration::findOrFail($id);

        if ($registration->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending registrations can be approved.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = auth()->guard('employee')->user();

            $registration->status = 'approved';
            $registration->approved_by = $user->id;
            $registration->approved_at = now();

            // Add approval history
            $approvalHistory = $registration->approvalHistory ?? [];
            $approvalHistory[] = [
                'approved_by' => $user->id,
                'approved_by_name' => $user->name,
                'approved_at' => now()->toDateTimeString(),
                'comments' => $request->comments ?? 'Approved',
            ];
            $registration->approvalHistory = $approvalHistory;
            $registration->save();

            activity()
                ->performedOn($registration)
                ->causedBy($user)
                ->log('approved registration');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration approved successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject registration
     */
    public function reject(Request $request, $id)
    {
        $this->authorize('reject_registrations');

        $registration = EventRegistration::findOrFail($id);

        if ($registration->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending registrations can be rejected.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = auth()->guard('employee')->user();

            $registration->status = 'rejected';
            $registration->rejected_by = $user->id;
            $registration->rejected_at = now();
            $registration->rejection_reason = $request->rejection_reason;
            $registration->save();

            activity()
                ->performedOn($registration)
                ->causedBy($user)
                ->withProperties(['reason' => $request->rejection_reason])
                ->log('rejected registration');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration rejected successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify registration document
     */
    public function verifyDocument(Request $request, $documentId)
    {
        $this->authorize('verify_documents');

        $document = RegistrationDocument::findOrFail($documentId);

        $validator = Validator::make($request->all(), [
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->guard('employee')->user();

        $document->verification_status = $request->verification_status;
        $document->verified_by = $user->id;
        $document->verified_at = now();
        $document->verification_notes = $request->verification_notes;
        $document->save();

        // Check if all documents are verified
        $registration = $document->eventRegistration;
        $allVerified = $registration->documents()
            ->where('verification_status', '!=', 'verified')
            ->count() === 0;

        if ($allVerified) {
            $registration->documents_verified = true;
            $registration->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Document ' . $request->verification_status . ' successfully.',
            'all_verified' => $allVerified
        ]);
    }
}
