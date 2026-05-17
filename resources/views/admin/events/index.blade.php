@extends('layouts.admin')

@section('title', 'Events')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Event Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Events</li>
            </ol>
        </nav>
    </div>
    @can('create_events')
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Create Event
    </a>
    @endcan
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm bg-primary text-white text-center">
            <div class="card-body py-3"><h4 class="mb-0">{{ $statistics['total'] ?? 0 }}</h4><small>Total</small></div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm bg-success text-white text-center">
            <div class="card-body py-3"><h4 class="mb-0">{{ $statistics['published'] ?? 0 }}</h4><small>Published</small></div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm bg-info text-white text-center">
            <div class="card-body py-3"><h4 class="mb-0">{{ $statistics['upcoming'] ?? 0 }}</h4><small>Upcoming</small></div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm bg-warning text-white text-center">
            <div class="card-body py-3"><h4 class="mb-0">{{ $statistics['ongoing'] ?? 0 }}</h4><small>Ongoing</small></div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm bg-secondary text-white text-center">
            <div class="card-body py-3"><h4 class="mb-0">{{ $statistics['completed'] ?? 0 }}</h4><small>Completed</small></div>
        </div>
    </div>
</div>

{{-- Events Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="eventsTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Banner</th>
                        <th>Event Details</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Registrations</th>
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
    $('#eventsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.events.index") }}',
        columns: [
            { data: 'banner', name: 'banner_image', orderable: false, searchable: false },
            { data: 'event_details', name: 'event_name' },
            { data: 'type_badge', name: 'event_type' },
            { data: 'dates', name: 'start_date', orderable: false },
            { data: 'registrations_count', name: 'registrations_count', orderable: false },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        responsive: true
    });

    // Publish event
    $(document).on('click', '.publish-event', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Publish Event?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Publish'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/events/${id}/publish`,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: (r) => { Swal.fire('Published!', r.message, 'success'); $('#eventsTable').DataTable().ajax.reload(); },
                    error: (x) => Swal.fire('Error', x.responseJSON?.message, 'error')
                });
            }
        });
    });

    // Delete event
    $(document).on('click', '.delete-event', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Event?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/events/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => { $('#eventsTable').DataTable().ajax.reload(); Swal.fire('Deleted!', '', 'success'); },
                    error: (x) => Swal.fire('Error', x.responseJSON?.message, 'error')
                });
            }
        });
    });
});
</script>
@endpush
