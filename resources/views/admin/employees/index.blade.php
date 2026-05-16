@extends('layouts.admin')

@section('title', 'Employee Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="bi bi-people me-2"></i>Employee Management
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            @can('create_employees')
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Add Employee
            </a>
            @endcan
            @can('import_employees')
            <a href="{{ route('admin.employees.import') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
            </a>
            @endcan
            @can('export_employees')
            <button class="btn btn-info" onclick="exportEmployees()">
                <i class="bi bi-download me-1"></i>Export
            </button>
            @endcan
        </div>
    </div>
</div>

{{-- Filters Section --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Region</label>
                <select name="region_id" id="regionFilter" class="form-select">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Airport</label>
                <select name="airport_id" id="airportFilter" class="form-select">
                    <option value="">All Airports</option>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="employment_status" id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="retired">Retired</option>
                    <option value="transferred">Transferred</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select name="gender" id="genderFilter" class="form-select">
                    <option value="">All Genders</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Employees Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="employeesTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Employee Details</th>
                        <th>Region</th>
                        <th>Airport</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Status Update Modal --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Employee Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="retired">Retired</option>
                            <option value="transferred">Transferred</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Password Reset Modal --}}
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="passwordForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                        <small class="text-muted">Minimum 8 characters with uppercase, lowercase, number and special character</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.employees.index") }}',
            data: function(d) {
                d.region_id = $('#regionFilter').val();
                d.airport_id = $('#airportFilter').val();
                d.employment_status = $('#statusFilter').val();
                d.gender = $('#genderFilter').val();
            }
        },
        columns: [
            { data: 'profile_photo', name: 'profile_photo', orderable: false, searchable: false },
            { data: 'full_details', name: 'name' },
            { data: 'region_name', name: 'region_name' },
            { data: 'airport_name', name: 'airport_name' },
            { data: 'role_badge', name: 'role_badge', orderable: false },
            { data: 'status', name: 'employment_status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            search: "Search employees:",
            lengthMenu: "Show _MENU_ employees per page",
            info: "Showing _START_ to _END_ of _TOTAL_ employees",
            infoEmpty: "No employees found",
            emptyTable: "No employees available"
        }
    });

    // Apply filters
    $('#filterForm select').change(function() {
        table.draw();
    });

    // Delete employee
    $(document).on('click', '.delete-employee', function() {
        let employeeId = $(this).data('id');
        let employeeName = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete employee: ${employeeName}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/employees/${employeeId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                        table.draw();
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Failed to delete employee',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Toggle status
    $(document).on('click', '.toggle-status', function() {
        let employeeId = $(this).data('id');
        let currentStatus = $(this).data('status');

        $('#statusForm').attr('data-id', employeeId);
        $('#statusForm select[name="status"]').val(currentStatus);
        $('#statusModal').modal('show');
    });

    $('#statusForm').submit(function(e) {
        e.preventDefault();
        let employeeId = $(this).attr('data-id');
        let status = $(this).find('select[name="status"]').val();

        $.ajax({
            url: `/admin/employees/${employeeId}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                $('#statusModal').modal('hide');
                Swal.fire('Updated!', response.message, 'success');
                table.draw();
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message, 'error');
            }
        });
    });

    // Reset password
    $(document).on('click', '.reset-password', function() {
        let employeeId = $(this).data('id');
        $('#passwordForm').attr('data-id', employeeId);
        $('#passwordModal').modal('show');
    });

    $('#passwordForm').submit(function(e) {
        e.preventDefault();
        let employeeId = $(this).attr('data-id');
        let formData = $(this).serialize();

        $.ajax({
            url: `/admin/employees/${employeeId}/reset-password`,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#passwordModal').modal('hide');
                Swal.fire('Success!', response.message, 'success');
                $('#passwordForm')[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (let key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                    Swal.fire('Validation Error!', errorMessage, 'error');
                } else {
                    Swal.fire('Error!', 'Failed to reset password', 'error');
                }
            }
        });
    });
});

function resetFilters() {
    $('#filterForm select').val('');
    $('#employeesTable').DataTable().draw();
}

function exportEmployees() {
    let filters = {
        region_id: $('#regionFilter').val(),
        airport_id: $('#airportFilter').val(),
        employment_status: $('#statusFilter').val(),
        gender: $('#genderFilter').val()
    };

    let queryString = $.param(filters);
    window.location.href = `{{ route("admin.employees.export") }}?${queryString}`;
}

// Region-Airport dependency
$('#regionFilter').change(function() {
    let regionId = $(this).val();
    if (regionId) {
        $.get(`/api/airports-by-region/${regionId}`, function(data) {
            let options = '<option value="">All Airports</option>';
            data.forEach(function(airport) {
                options += `<option value="${airport.id}">${airport.name}</option>`;
            });
            $('#airportFilter').html(options);
        });
    } else {
        $('#airportFilter').html('<option value="">All Airports</option>');
    }
});
</script>
@endpush
