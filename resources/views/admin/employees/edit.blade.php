@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Employee</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">Edit: {{ $employee->name }}</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Personal Information --}}
                <div class="col-12">
                    <h5 class="mb-3 text-primary"><i class="bi bi-person me-2"></i>Personal Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $employee->employee_id) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">PAN Number <span class="text-danger">*</span></label>
                    <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number', $employee->pan_number) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" {{ $employee->gender == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $employee->date_of_birth->format('Y-m-d')) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Blood Group</label>
                    <select name="blood_group" class="form-select">
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ $employee->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sports Category</label>
                    <select name="sports_category" class="form-select">
                        <option value="">Select</option>
                        @foreach(['Cricket','Football','Badminton','Table Tennis','Chess','Athletics','Volleyball','Basketball'] as $sport)
                            <option value="{{ $sport }}" {{ $employee->sports_category == $sport ? 'selected' : '' }}>{{ $sport }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Contact --}}
                <div class="col-12 mt-3"><h5 class="mb-3 text-primary"><i class="bi bi-envelope me-2"></i>Contact</h5></div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $employee->mobile) }}">
                </div>

                {{-- Employment --}}
                <div class="col-12 mt-3"><h5 class="mb-3 text-primary"><i class="bi bi-briefcase me-2"></i>Employment</h5></div>

                <div class="col-md-4">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $employee->designation) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select">
                        @foreach(['Operations','Security','Engineering','Fire Services','HR','Finance','IT','Commercial'] as $dept)
                            <option value="{{ $dept }}" {{ $employee->department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="employment_status" class="form-select">
                        @foreach(['active','inactive','retired','transferred'] as $status)
                            <option value="{{ $status }}" {{ $employee->employment_status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Region</label>
                    <select name="region_id" class="form-select">
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ $employee->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Airport</label>
                    <select name="airport_id" class="form-select">
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" {{ $employee->airport_id == $airport->id ? 'selected' : '' }}>{{ $airport->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                    @if($employee->profile_photo)
                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" class="mt-2 rounded" width="60">
                    @endif
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Employee</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
