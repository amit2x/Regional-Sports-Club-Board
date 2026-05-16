@extends('layouts.admin')

@section('title', 'Registration Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="bi bi-clipboard-check me-2"></i>Registration Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.registrations.pending') }}">Registrations</a></li>
                    <li class="breadcrumb-item active">{{ $registration->registration_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($registration->status === 'pending')
                @can('approve_registrations')
                <button class="btn btn-success me-2" onclick="approveRegistration()">
                    <i class="bi bi-check-lg me-1"></i>Approve
                </button>
                @endcan
                @can('reject_registrations')
                <button class="btn btn-danger" onclick="showRejectModal()">
                    <i class="bi bi-x-lg me-1"></i>Reject
                </button>
                @endcan
            @endif
            <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Registration Info --}}
    <div class="col-lg-8">
        {{-- Status Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">Registration #{{ $registration->registration_number }}</h4>
                        <p class="text-muted mb-0">
                            Submitted on {{ $registration->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'withdrawn' => 'info'
                            ];
                            $color = $statusColors[$registration->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }} fs-6">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employee Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person me-2"></i>Employee Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Name</label>
                        <p class="mb-2">{{ $registration->employee->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Employee ID</label>
                        <p class="mb-2">{{ $registration->employee->employee_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Department</label>
                        <p class="mb-2">{{ $registration->employee->department }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Designation</label>
                        <p class="mb-2">{{ $registration->employee->designation }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Airport</label>
                        <p class="mb-2">{{ $registration->employee->airport->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Region</label>
                        <p class="mb-2">{{ $registration->employee->region->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-event me-2"></i>Event Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Event Name</label>
                        <p class="mb-2">{{ $registration->event->event_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Event Code</label>
                        <p class="mb-2">{{ $registration->event->event_code }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Venue</label>
                        <p class="mb-2">{{ $registration->event->venue }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Dates</label>
                        <p class="mb-2">
                            {{ $registration->event->start_date->format('d M Y') }} -
                            {{ $registration->event->end_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Data --}}
        @if($registration->form_data)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-text me-2"></i>Registration Form Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($registration->form_data as $key => $value)
                            @if($key !== '_token')
                                <div class="col-md-6">
                                    <label class="text-muted small">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                    <p class="mb-2">
                                        @if(is_array($value))
                                            {{ implode(', ', $value) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Documents --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-file-earmark me-2"></i>Documents
                </h5>
                @if($registration->documents_verified)
                    <span class="badge bg-success">All Verified</span>
                @else
                    <span class="badge bg-warning">Pending Verification</span>
                @endif
            </div>
            <div class="card-body">
                @forelse($registration->documents as $document)
                    <div class="document-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</strong>
                                <br>
                                <small class="text-muted">{{ $document->document_name }}</small>
                            </div>
                            <span class="badge bg-{{ $document->verification_status === 'verified' ? 'success' : ($document->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($document->verification_status) }}
                            </span>
                        </div>

                        <div class="btn-group w-100">
                            <a href="{{ asset('storage/' . $document->file_path) }}"
                               class="btn btn-sm btn-outline-primary"
                               target="_blank">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                            <a href="{{ asset('storage/' . $document->file_path) }}"
                               class="btn btn-sm btn-outline-success"
                               download>
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>

                        @can('verify_documents')
                            @if($document->verification_status === 'pending' && $registration->status === 'pending')
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-success w-100 mb-1"
                                            onclick="verifyDocument({{ $document->id }}, 'verified')">
                                        <i class="bi bi-check-lg me-1"></i>Verify
                                    </button>
                                    <button class="btn btn-sm btn-danger w-100"
                                            onclick="showRejectDocumentModal({{ $document->id }})">
                                        <i class="bi bi-x-lg me-1"></i>Reject
                                    </button>
                                </div>
                            @endif
                        @endcan
                    </div>
                @empty
                    <p class="text-center text-muted">No documents uploaded</p>
                @endforelse
            </div>
        </div>

        {{-- Approval History --}}
        @if($approvalHistory && count($approvalHistory) > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Approval History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($approvalHistory as $history)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $history['level'] === 'final' ? 'success' : 'primary' }}"></div>
                                <div class="timeline-content">
                                    <p class="mb-0">
                                        <strong>{{ $history['approved_by_name'] }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $history['level'] }} level approval</small>
                                    </p>
                                    <small>{{ $history['comments'] ?? 'No comments' }}</small>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($history['approved_at'])->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Rejection Reason *</label>
                    <textarea id="rejectionReason" class="form-control" rows="4" required
                              placeholder="Please provide a detailed reason for rejection..."></textarea>
                    <small class="text-muted">Minimum 10 characters</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="rejectRegistration()">
                    <i class="bi bi-x-lg me-1"></i>Reject Registration
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Reject Document Modal --}}
<div class="modal fade" id="rejectDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rejectDocumentId">
                <div class="mb-3">
                    <label class="form-label">Rejection Notes</label>
                    <textarea id="rejectDocumentNotes" class="form-control" rows="3"
                              placeholder="Why is this document being rejected?"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="rejectDocument()">
                    Reject Document
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const registrationId = {{ $registration->id }};

function approveRegistration() {
    Swal.fire({
        title: 'Approve Registration?',
        text: 'Are you sure you want to approve this registration?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/registrations/${registrationId}/approve`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    comments: 'Approved'
                },
                success: function(response) {
                    Swal.fire('Approved!', response.message, 'success')
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to approve', 'error');
                }
            });
        }
    });
}

function showRejectModal() {
    $('#rejectModal').modal('show');
}

function rejectRegistration() {
    let reason = $('#rejectionReason').val().trim();

    if (reason.length < 10) {
        Swal.fire('Required', 'Please provide a detailed rejection reason (min 10 characters)', 'warning');
        return;
    }

    $.ajax({
        url: `/admin/registrations/${registrationId}/reject`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            rejection_reason: reason
        },
        success: function(response) {
            $('#rejectModal').modal('hide');
            Swal.fire('Rejected!', response.message, 'success')
                .then(() => location.reload());
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to reject', 'error');
        }
    });
}

function verifyDocument(documentId, status) {
    $.ajax({
        url: `/admin/documents/${documentId}/verify`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            verification_status: status,
            verification_notes: 'Verified'
        },
        success: function(response) {
            Swal.fire('Verified!', response.message, 'success')
                .then(() => location.reload());
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to verify document', 'error');
        }
    });
}

function showRejectDocumentModal(documentId) {
    $('#rejectDocumentId').val(documentId);
    $('#rejectDocumentModal').modal('show');
}

function rejectDocument() {
    let documentId = $('#rejectDocumentId').val();
    let notes = $('#rejectDocumentNotes').val().trim();

    $.ajax({
        url: `/admin/documents/${documentId}/verify`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            verification_status: 'rejected',
            verification_notes: notes || 'Document rejected'
        },
        success: function(response) {
            $('#rejectDocumentModal').modal('hide');
            Swal.fire('Rejected!', 'Document rejected successfully', 'success')
                .then(() => location.reload());
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to reject document', 'error');
        }
    });
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -24px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.timeline-content {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
}
</style>
@endpush
