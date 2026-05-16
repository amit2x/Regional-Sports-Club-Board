@extends('layouts.admin')

@section('title', 'Certificates')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Certificates</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Certificates</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal">
        <i class="bi bi-award me-1"></i>Bulk Generate
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Certificates</h5>
        <div class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm" placeholder="Search certificates..." id="certSearch" style="width: 250px;">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Certificate #</th>
                        <th>Employee</th>
                        <th>Event</th>
                        <th>Issue Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $certificate)
                    <tr>
                        <td><span class="fw-medium">{{ $certificate->certificate_number }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                    {{ strtoupper(substr($certificate->employee->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="mb-0 small fw-medium">{{ $certificate->employee->name ?? 'N/A' }}</p>
                                    <small class="text-muted">{{ $certificate->employee->employee_id ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 small">{{ $certificate->event->event_name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $certificate->event->event_code ?? '' }}</small>
                        </td>
                        <td>{{ $certificate->issue_date->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.certificates.download', $certificate->id) }}" class="btn btn-sm btn-success" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ asset('storage/' . $certificate->pdf_path) }}" target="_blank" class="btn btn-sm btn-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-cert" data-id="{{ $certificate->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="bi bi-award text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">No certificates generated yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $certificates->links() }}
    </div>
</div>

{{-- Bulk Generate Modal --}}
<div class="modal fade" id="bulkGenerateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-award me-2"></i>Bulk Generate Certificates</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkGenerateForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Event</label>
                        <select name="event_id" class="form-select" required id="bulkEventSelect">
                            <option value="">Choose event...</option>
                            @foreach(\App\Models\Event::where('status', 'completed')->get() as $event)
                                <option value="{{ $event->id }}">{{ $event->event_name }} ({{ $event->event_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Approved Registrations</label>
                        <div id="bulkRegistrationsList" class="border rounded-3 p-3" style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted text-center mb-0">Select an event to view registrations</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Certificates</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#certSearch').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Delete certificate
    $('.delete-cert').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Certificate?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/certificates/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => location.reload(),
                    error: (x) => Swal.fire('Error', 'Failed to delete', 'error')
                });
            }
        });
    });

    // Load registrations on event select
    $('#bulkEventSelect').change(function() {
        const eventId = $(this).val();
        if (eventId) {
            $.get(`/admin/events/${eventId}/registrations`, function(data) {
                let html = '';
                data.forEach(reg => {
                    html += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="registration_ids[]" value="${reg.id}" id="reg${reg.id}">
                            <label class="form-check-label small" for="reg${reg.id}">
                                ${reg.employee.name} (${reg.registration_number})
                            </label>
                        </div>`;
                });
                $('#bulkRegistrationsList').html(html || '<p class="text-muted text-center mb-0">No approved registrations found</p>');
            });
        }
    });

    // Bulk generate
    $('#bulkGenerateForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.certificates.bulk-generate") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: (r) => {
                $('#bulkGenerateModal').modal('hide');
                Swal.fire('Success!', r.message, 'success').then(() => location.reload());
            },
            error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
        });
    });
});
</script>
@endpush
