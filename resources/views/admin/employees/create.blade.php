@extends('layouts.admin')

@section('title', 'Add Employee')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Employee</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="row g-3">
                {{-- Personal Information --}}
                <div class="col-12">
                    <h5 class="mb-3 text-primary"><i class="bi bi-person me-2"></i>Personal Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}" required>
                    <div class="invalid-feedback">Employee ID is required.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">PAN Number <span class="text-danger">*</span></label>
                    <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number') }}"
                           placeholder="ABCDE1234F" maxlength="10" required>
                    <small class="text-muted">Format: ABCDE1234F</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Blood Group</label>
                    <select name="blood_group" class="form-select">
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sports Category</label>
                    <select name="sports_category" class="form-select">
                        <option value="">Select</option>
                        @foreach(['Cricket','Football','Badminton','Table Tennis','Chess','Athletics','Volleyball','Basketball','Swimming','Tennis'] as $sport)
                            <option value="{{ $sport }}" {{ old('sports_category') == $sport ? 'selected' : '' }}>{{ $sport }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Contact Information --}}
                <div class="col-12 mt-3">
                    <h5 class="mb-3 text-primary"><i class="bi bi-envelope me-2"></i>Contact Information</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}"
                           placeholder="10-digit number" maxlength="10">
                </div>

                {{-- Employment Information --}}
                <div class="col-12 mt-3">
                    <h5 class="mb-3 text-primary"><i class="bi bi-briefcase me-2"></i>Employment Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <select name="department" class="form-select" required>
                        <option value="">Select</option>
                        @foreach(['Operations','Security','Engineering','Fire Services','HR','Finance','IT','Commercial','Administration'] as $dept)
                            <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employment Status</label>
                    <select name="employment_status" class="form-select">
                        <option value="active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('employment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Region <span class="text-danger">*</span></label>
                    <select name="region_id" id="regionSelect" class="form-select" required>
                        <option value="">Select Region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }} ({{ $region->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Airport <span class="text-danger">*</span></label>
                    <select name="airport_id" id="airportSelect" class="form-select" required>
                        <option value="">Select Airport</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" data-region="{{ $airport->region_id }}"
                                {{ old('airport_id') == $airport->id ? 'selected' : '' }}>
                                {{ $airport->name }} ({{ $airport->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Medical Information --}}
                <div class="col-12 mt-3">
                    <h5 class="mb-3 text-primary"><i class="bi bi-heart-pulse me-2"></i>Medical Information</h5>
                </div>

                <div class="col-12">
                    <label class="form-label">Medical Conditions</label>
                    <textarea name="medical_conditions" class="form-control" rows="2"
                              placeholder="Any medical conditions or allergies...">{{ old('medical_conditions') }}</textarea>
                </div>

                {{-- Profile Photo --}}
                <div class="col-md-4">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                    <small class="text-muted">Max size: 2MB. JPG, PNG</small>
                </div>

                {{-- Submit --}}
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Create Employee
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter airports by region
    $('#regionSelect').change(function() {
        const regionId = $(this).val();
        const airportSelect = $('#airportSelect');

        airportSelect.find('option').each(function() {
            if ($(this).val() === '') return;
            const airportRegion = $(this).data('region');
            $(this).toggle(!regionId || airportRegion == regionId);
        });

        if (!airportSelect.find('option:visible:not([value=""])').length) {
            airportSelect.val('');
        }
    });

    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
});
</script>
@endpush
