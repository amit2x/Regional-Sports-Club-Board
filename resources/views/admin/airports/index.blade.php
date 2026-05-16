@extends('layouts.admin')

@section('title', 'Airports')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Airport Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Airports</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#airportModal" onclick="clearForm()">
        <i class="bi bi-plus-lg me-1"></i>Add Airport
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="airportsTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Region</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Airport Modal --}}
<div class="modal fade" id="airportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="airportModalTitle">Add Airport</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="airportForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="airportMethod">
                <input type="hidden" name="id" id="airportId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="airportName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" id="airportCode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" id="airportCity" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control" id="airportState">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Region <span class="text-danger">*</span></label>
                        <select name="region_id" class="form-select" id="airportRegion" required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" id="airportContactPerson">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" id="airportContactNumber">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="airportEmail">
                    </div>
                    <div class="mb-3" id="statusField" style="display:none;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="airportStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="airportSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#airportsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.airports.index") }}',
        columns: [
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'city', name: 'city' },
            { data: 'region_name', name: 'region.name' },
            { data: 'employees_count', name: 'employees_count' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        responsive: true
    });

    // Edit button
    $(document).on('click', '.edit-airport', function() {
        const id = $(this).data('id');
        $.get(`/admin/airports/${id}/edit`, function(data) {
            // Populate form (if using API endpoint)
        });

        $.get(`/admin/airports/${id}`, function() {
            // Get airport data and fill form
        });

        $('#airportModalTitle').text('Edit Airport');
        $('#airportMethod').val('PUT');
        $('#airportId').val(id);
        $('#statusField').show();
        $('#airportSubmitBtn').text('Update');
        $('#airportModal').modal('show');
    });

    // Submit form
    $('#airportForm').submit(function(e) {
        e.preventDefault();
        const id = $('#airportId').val();
        const url = id ? `/admin/airports/${id}` : '{{ route("admin.airports.store") }}';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: (r) => {
                $('#airportModal').modal('hide');
                Swal.fire('Saved!', r.message, 'success');
                table.ajax.reload();
            },
            error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
        });
    });

    // Delete button
    $(document).on('click', '.delete-airport', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Airport?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/airports/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => { table.ajax.reload(); Swal.fire('Deleted!', '', 'success'); },
                    error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
                });
            }
        });
    });
});

function clearForm() {
    $('#airportForm')[0].reset();
    $('#airportId').val('');
    $('#airportMethod').val('POST');
    $('#airportModalTitle').text('Add Airport');
    $('#statusField').hide();
    $('#airportSubmitBtn').text('Save');
}
</script>
@endpush
