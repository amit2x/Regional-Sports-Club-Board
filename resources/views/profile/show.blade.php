@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Profile</h1>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="mb-3">
                    @if($employee->profile_photo)
                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <h4>{{ $employee->name }}</h4>
                <p class="text-muted">{{ $employee->employee_id }}</p>
                <span class="badge bg-success">{{ ucfirst($employee->employment_status) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Personal Information</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted">Designation</small><p class="mb-0">{{ $employee->designation }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Department</small><p class="mb-0">{{ $employee->department }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Email</small><p class="mb-0">{{ $employee->email }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Mobile</small><p class="mb-0">{{ $employee->mobile ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Airport</small><p class="mb-0">{{ $employee->airport->name ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Region</small><p class="mb-0">{{ $employee->region->name ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Gender</small><p class="mb-0">{{ ucfirst($employee->gender) }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Date of Birth</small><p class="mb-0">{{ $employee->date_of_birth->format('d M Y') }} ({{ $employee->age }} years)</p></div>
                    <div class="col-md-6"><small class="text-muted">Blood Group</small><p class="mb-0">{{ $employee->blood_group ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Sports Category</small><p class="mb-0">{{ $employee->sports_category ?? 'N/A' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


