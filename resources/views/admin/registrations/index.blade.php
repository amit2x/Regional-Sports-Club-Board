@extends('layouts.admin')

@section('title', 'All Registrations')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">All Registrations</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Registrations</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.registrations.pending') }}" class="btn btn-warning">
        <i class="bi bi-hourglass-split me-1"></i>Pending Approvals
        @if($stats['pending'] > 0)
            <span class="badge bg-dark ms-1">{{ $stats['pending'] }}</span>
        @endif
    </a>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                <small>Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                <small>Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                <small>Approved</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                <small>Rejected</small>
            </div>
        </div>
    </div>
</div>

{{-- Registrations Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="registrationsTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Registration #</th>
                        <th>Employee</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#registrationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.registrations.index") }}',
        columns: [
            { data: 'registration_number', name: 'registration_number' },
            { data: 'employee_details', name: 'employee.name' },
            { data: 'event_details', name: 'event.event_name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[3, 'desc']],
        responsive: true
    });
});
</script>
@endpush
