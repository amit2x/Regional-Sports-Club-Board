@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Announcements</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Announcements</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Announcement
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="announcementsTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Valid Until</th>
                        <th>Views</th>
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
    $('#announcementsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.announcements.index") }}',
        columns: [
            { data: 'title_preview', name: 'title' },
            { data: 'priority_badge', name: 'priority' },
            { data: 'status_badge', name: 'status' },
            { data: 'valid_until', name: 'valid_until' },
            { data: 'views_count', name: 'views_count' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        responsive: true
    });

    // Publish announcement
    $(document).on('click', '.publish-announcement', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/admin/announcements/${id}/publish`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: (r) => { Swal.fire('Published!', r.message, 'success'); $('#announcementsTable').DataTable().ajax.reload(); },
            error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
        });
    });

    // Delete announcement
    $(document).on('click', '.delete-announcement', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Announcement?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/announcements/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => { $('#announcementsTable').DataTable().ajax.reload(); Swal.fire('Deleted!', '', 'success'); },
                    error: (x) => Swal.fire('Error', 'Failed to delete', 'error')
                });
            }
        });
    });
});
</script>
@endpush
