@extends('layouts.admin')

@section('title', 'Event Registrations - ' . $event->event_name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Registrations: {{ $event->event_name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                <li class="breadcrumb-item active">Registrations</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-info">
            <i class="bi bi-eye me-1"></i>Event Details
        </a>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

{{-- Event Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="mb-0">{{ $registrations->count() }}</h4>
                <small>Total Registrations</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="mb-0">{{ $registrations->where('status', 'approved')->count() }}</h4>
                <small>Approved</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="mb-0">{{ $registrations->where('status', 'pending')->count() }}</h4>
                <small>Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body text-center py-3">
                <h4 class="mb-0">{{ $registrations->where('status', 'rejected')->count() }}</h4>
                <small>Rejected</small>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Filter by Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="withdrawn">Withdrawn</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Filter by Gender</label>
                <select class="form-select" id="genderFilter">
                    <option value="">All Genders</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search by name, employee ID...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Registrations Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Registration List</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-success" onclick="exportRegistrations()">
                <i class="bi bi-download me-1"></i>Export
            </button>
            @can('generate_certificates')
            <button class="btn btn-sm btn-info" onclick="bulkGenerateCertificates()">
                <i class="bi bi-award me-1"></i>Generate Certificates
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="registrationsTable">
                <thead class="table-light">
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input" id="selectAll" title="Select All">
                        </th>
                        <th>Reg #</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Airport</th>
                        <th>Submitted</th>
                        <th>Documents</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                    <tr class="registration-row"
                        data-status="{{ $registration->status }}"
                        data-gender="{{ $registration->employee->gender ?? '' }}">
                        <td>
                            <input type="checkbox" class="form-check-input reg-checkbox"
                                   value="{{ $registration->id }}"
                                   {{ $registration->status !== 'approved' ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <span class="fw-medium">{{ $registration->registration_number }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 32px; height: 32px; font-size: 12px;">
                                    {{ strtoupper(substr($registration->employee->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="mb-0 small fw-medium">{{ $registration->employee->name ?? 'N/A' }}</p>
                                    <small class="text-muted">{{ $registration->employee->employee_id ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><small>{{ $registration->employee->department ?? 'N/A' }}</small></td>
                        <td><small>{{ $registration->employee->airport->code ?? 'N/A' }}</small></td>
                        <td><small>{{ $registration->created_at->format('d M Y') }}</small></td>
                        <td>
                            @php
                                $docCount = $registration->documents->count();
                                $verifiedCount = $registration->documents->where('verification_status', 'verified')->count();
                            @endphp
                            @if($docCount > 0)
                                <span class="badge bg-{{ $verifiedCount === $docCount ? 'success' : 'warning' }}">
                                    {{ $verifiedCount }}/{{ $docCount }}
                                </span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </td>
                        <td>
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
                            <span class="badge bg-{{ $color }}">{{ ucfirst($registration->status) }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.registrations.show', $registration->id) }}"
                                   class="btn btn-sm btn-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($registration->status === 'pending')
                                    @can('approve_registrations')
                                    <button class="btn btn-sm btn-success approve-single"
                                            data-id="{{ $registration->id }}"
                                            title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    @endcan
                                    @can('reject_registrations')
                                    <button class="btn btn-sm btn-danger reject-single"
                                            data-id="{{ $registration->id }}"
                                            title="Reject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @endcan
                                @endif
                                @can('generate_certificates')
                                    @if($registration->status === 'approved' && !$registration->certificate)
                                    <button class="btn btn-sm btn-success generate-cert"
                                            data-id="{{ $registration->id }}"
                                            title="Generate Certificate">
                                        <i class="bi bi-award"></i>
                                    </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">No registrations found for this event.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($registrations->count() > 0)
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $registrations->count() }} registrations</small>
            @can('approve_registrations')
            <button class="btn btn-sm btn-success" id="bulkApproveBtn" disabled>
                <i class="bi bi-check-all me-1"></i>Bulk Approve
            </button>
            @endcan
        </div>
    </div>
    @endif
</div>

{{-- Approve Modal --}}
<div class="modal fade" id="approveSingleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Approve Registration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this registration?</p>
                <div class="mb-3">
                    <label class="form-label">Comments (Optional)</label>
                    <textarea id="approveComments" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprove">Approve</button>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectSingleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Registration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                    <textarea id="rejectReason" class="form-control" rows="4" required
                              placeholder="Provide a detailed reason..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentRegistrationId = null;

    // Filter functionality
    $('#statusFilter, #genderFilter').change(function() {
        filterTable();
    });

    $('#searchInput').keyup(function() {
        filterTable();
    });

    function filterTable() {
        const status = $('#statusFilter').val().toLowerCase();
        const gender = $('#genderFilter').val().toLowerCase();
        const search = $('#searchInput').val().toLowerCase();

        $('.registration-row').each(function() {
            const row = $(this);
            const rowStatus = row.data('status');
            const rowGender = row.data('gender');
            const rowText = row.text().toLowerCase();

            let show = true;

            if (status && rowStatus !== status) show = false;
            if (gender && rowGender !== gender) show = false;
            if (search && !rowText.includes(search)) show = false;

            row.toggle(show);
        });
    }

    // Select All
    $('#selectAll').change(function() {
        const checked = $(this).prop('checked');
        $('.reg-checkbox:not(:disabled)').prop('checked', checked);
        updateBulkButtons();
    });

    $('.reg-checkbox').change(function() {
        updateBulkButtons();
    });

    function updateBulkButtons() {
        const checkedCount = $('.reg-checkbox:checked').length;
        $('#bulkApproveBtn').prop('disabled', checkedCount === 0);
        $('#bulkApproveBtn').text(`Bulk Approve (${checkedCount})`);
    }

    // Single Approve
    $(document).on('click', '.approve-single', function() {
        currentRegistrationId = $(this).data('id');
        $('#approveSingleModal').modal('show');
    });

    $('#confirmApprove').click(function() {
        if (!currentRegistrationId) return;

        $.ajax({
            url: `/admin/registrations/${currentRegistrationId}/approve`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                comments: $('#approveComments').val()
            },
            success: function(response) {
                $('#approveSingleModal').modal('hide');
                Swal.fire('Approved!', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to approve', 'error');
            }
        });
    });

    // Single Reject
    $(document).on('click', '.reject-single', function() {
        currentRegistrationId = $(this).data('id');
        $('#rejectSingleModal').modal('show');
    });

    $('#confirmReject').click(function() {
        if (!currentRegistrationId) return;

        const reason = $('#rejectReason').val().trim();
        if (reason.length < 10) {
            Swal.fire('Required', 'Please provide at least 10 characters for rejection reason', 'warning');
            return;
        }

        $.ajax({
            url: `/admin/registrations/${currentRegistrationId}/reject`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                rejection_reason: reason
            },
            success: function(response) {
                $('#rejectSingleModal').modal('hide');
                Swal.fire('Rejected!', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to reject', 'error');
            }
        });
    });

    // Bulk Approve
    $('#bulkApproveBtn').click(function() {
        const ids = $('.reg-checkbox:checked').map(function() { return $(this).val(); }).get();

        if (ids.length === 0) {
            Swal.fire('Select', 'Please select registrations to approve', 'warning');
            return;
        }

        Swal.fire({
            title: `Approve ${ids.length} registrations?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Approve All'
        }).then((result) => {
            if (result.isConfirmed) {
                let promises = ids.map(id => {
                    return $.ajax({
                        url: `/admin/registrations/${id}/approve`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', comments: 'Bulk approved' }
                    });
                });

                Promise.all(promises)
                    .then(() => {
                        Swal.fire('Approved!', `${ids.length} registrations approved`, 'success')
                            .then(() => location.reload());
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Some approvals failed', 'error');
                    });
            }
        });
    });

    // Generate Certificate
    $(document).on('click', '.generate-cert', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/admin/certificates/generate/${id}`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                Swal.fire('Generated!', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
            }
        });
    });

    // Modals cleanup
    $('#approveSingleModal, #rejectSingleModal').on('hidden.bs.modal', function() {
        currentRegistrationId = null;
        $(this).find('textarea').val('');
    });
});

function resetFilters() {
    $('#statusFilter, #genderFilter').val('');
    $('#searchInput').val('');
    $('.registration-row').show();
}

function exportRegistrations() {
    window.location.href = '{{ route("admin.events.export", $event->id) }}';
}

function bulkGenerateCertificates() {
    const ids = $('.reg-checkbox:checked').map(function() { return $(this).val(); }).get();

    if (ids.length === 0) {
        Swal.fire('Select', 'Please select approved registrations', 'warning');
        return;
    }

    $.ajax({
        url: '{{ route("admin.certificates.bulk-generate") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            event_id: {{ $event->id }},
            registration_ids: ids
        },
        success: function(response) {
            Swal.fire('Generated!', response.message, 'success').then(() => location.reload());
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
        }
    });
}
</script>
@endpush
