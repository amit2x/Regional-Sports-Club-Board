@extends('layouts.public')

@section('title', 'My Profile')

@section('content')
<div class="page-content container">
    {{-- Profile Header --}}
    <div class="text-center mb-4">
        @if($employee->profile_photo)
            <img src="{{ asset('storage/' . $employee->profile_photo) }}"
                 class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #667eea;">
        @else
            <div class="avatar-circle bg-primary mx-auto mb-3" style="width: 100px; height: 100px; font-size: 36px;">
                {{ strtoupper(substr($employee->name, 0, 2)) }}
            </div>
        @endif
        <h5>{{ $employee->name }}</h5>
        <small class="text-muted">{{ $employee->employee_id }}</small>
        <br>
        <span class="status-badge bg-success text-white mt-1">{{ ucfirst($employee->employment_status) }}</span>
    </div>

    {{-- Info Cards --}}
    <div class="employee-card">
        <div class="card-header"><i class="bi bi-person me-2"></i>Personal Info</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6"><small class="text-muted">Designation</small><p class="mb-0 fw-medium">{{ $employee->designation }}</p></div>
                <div class="col-6"><small class="text-muted">Department</small><p class="mb-0 fw-medium">{{ $employee->department }}</p></div>
                <div class="col-6"><small class="text-muted">Gender</small><p class="mb-0">{{ ucfirst($employee->gender) }}</p></div>
                <div class="col-6"><small class="text-muted">DOB</small><p class="mb-0">{{ $employee->date_of_birth->format('d M Y') }}</p></div>
                <div class="col-6"><small class="text-muted">Blood Group</small><p class="mb-0">{{ $employee->blood_group ?? 'N/A' }}</p></div>
                <div class="col-6"><small class="text-muted">Sports</small><p class="mb-0">{{ $employee->sports_category ?? 'N/A' }}</p></div>
            </div>
        </div>
    </div>

    <div class="employee-card">
        <div class="card-header"><i class="bi bi-envelope me-2"></i>Contact</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6"><small class="text-muted">Email</small><p class="mb-0">{{ $employee->email }}</p></div>
                <div class="col-6"><small class="text-muted">Mobile</small><p class="mb-0">{{ $employee->mobile ?? 'N/A' }}</p></div>
                <div class="col-6"><small class="text-muted">Airport</small><p class="mb-0">{{ $employee->airport->name ?? 'N/A' }}</p></div>
                <div class="col-6"><small class="text-muted">Region</small><p class="mb-0">{{ $employee->region->name ?? 'N/A' }}</p></div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <a href="{{ route('employee.password.change') }}" class="btn btn-outline-primary btn-mobile w-100 mb-2">
        <i class="bi bi-key me-1"></i>Change Password
    </a>
    <button onclick="document.getElementById('logoutForm').submit();" class="btn btn-outline-danger btn-mobile w-100">
        <i class="bi bi-box-arrow-right me-1"></i>Logout
    </button>
</div>
@endsection
