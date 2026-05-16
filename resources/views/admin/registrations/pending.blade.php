@extends('layouts.admin')

@section('title', 'Pending Registrations')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Pending Registrations</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
                <li class="breadcrumb-item active">Pending</li>
            </ol>
        </nav>
    </div>
    <span class="badge bg-warning fs-6">{{ $pendingCount }} Pending</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="pendingTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Registration #</th>
                        <th>Employee</th>
                        <th>Event</th>
                        <th>Submitted</th>
                        <th>Documents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Approve/Reject Modals --}}
@include('admin.registrations.partials.approve-modal')
@include('admin.registrations.partials.reject-modal')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#pendingTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.registrations.pending") }}',
        columns: [
            { data: 'registration_number', name: 'registration_number' },
            { data: 'employee_details', name: 'employee.name' },
            { data: 'event_details', name: 'event.event_name' },
            { data: 'submission_date', name: 'created_at' },
            { data: 'documents', name: 'documents', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[3, 'asc']],
        responsive: true
    });
});
</script>
@endpush
