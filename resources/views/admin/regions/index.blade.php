@extends('layouts.admin')

@section('title', 'Regions')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Region Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Regions</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#regionModal" onclick="clearRegionForm()">
        <i class="bi bi-plus-lg me-1"></i>Add Region
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="regionsTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Headquarters</th>
                        <th>Airports</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Region Modal --}}
<div class="modal fade" id="regionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="regionModalTitle">Add Region</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="regionForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="regionMethod">
                <input type="hidden" name="id" id="regionId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="regionName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" id="regionCode" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Headquarters</label>
                        <input type="text" name="headquarters" class="form-control" id="regionHeadquarters">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="regionDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3" id="regionStatusField" style="display:none;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="regionStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="regionSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#regionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.regions.index") }}',
        columns: [
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'headquarters', name: 'headquarters' },
            { data: 'airports_count', name: 'airports_count' },
            { data: 'employees_count', name: 'employees_count' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        responsive: true
    });

    // Edit button
    $(document).on('click', '.edit-region', function() {
        const id = $(this).data('id');
        $.get(`/admin/regions/${id}`, function(data) {
            $('#regionId').val(data.id);
            $('#regionName').val(data.name);
            $('#regionCode').val(data.code);
            $('#regionHeadquarters').val(data.headquarters);
            $('#regionDescription').val(data.description);
            $('#regionStatus').val(data.status);
        });

        $('#regionModalTitle').text('Edit Region');
        $('#regionMethod').val('PUT');
        $('#regionStatusField').show();
        $('#regionSubmitBtn').text('Update');
        $('#regionModal').modal('show');
    });

    // Submit form
    $('#regionForm').submit(function(e) {
        e.preventDefault();
        const id = $('#regionId').val();
        const url = id ? `/admin/regions/${id}` : '{{ route("admin.regions.store") }}';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: (r) => {
                $('#regionModal').modal('hide');
                Swal.fire('Saved!', r.message, 'success');
                table.ajax.reload();
            },
            error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
        });
    });

    // Delete button
    $(document).on('click', '.delete-region', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Region?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/regions/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => { table.ajax.reload(); Swal.fire('Deleted!', '', 'success'); },
                    error: (x) => Swal.fire('Error', x.responseJSON?.message || 'Failed', 'error')
                });
            }
        });
    });
});

function clearRegionForm() {
    $('#regionForm')[0].reset();
    $('#regionId').val('');
    $('#regionMethod').val('POST');
    $('#regionModalTitle').text('Add Region');
    $('#regionStatusField').hide();
    $('#regionSubmitBtn').text('Save');
}
</script>
@endpush
