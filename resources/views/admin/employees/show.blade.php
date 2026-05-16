@extends('layouts.admin')

@section('title', 'Employee Profile')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="bi bi-person-badge me-2"></i>Employee Profile
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
                    <li class="breadcrumb-item active">{{ $employee->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @can('edit_employees')
            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            @endcan
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Employee Details --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-4">
                    @if($employee->profile_photo)
                        <img src="{{ asset('storage/' . $employee->profile_photo) }}"
                             alt="{{ $employee->name }}"
                             class="rounded-circle border border-4 border-primary"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 150px; height: 150px; font-size: 48px;">
                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <h3 class="mb-1">{{ $employee->name }}</h3>
                <p class="text-muted mb-3">{{ $employee->employee_id }}</p>

                <span class="badge bg-{{ $employee->employment_status == 'active' ? 'success' : 'danger' }} mb-3">
                    {{ ucfirst($employee->employment_status) }}
                </span>

                <hr>

                <div class="text-start">
                    <div class="mb-3">
                        <small class="text-muted">Designation</small>
                        <p class="mb-1"><strong>{{ $employee->designation }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Department</small>
                        <p class="mb-1"><strong>{{ $employee->department }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <p class="mb-1">
                            <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Mobile</small>
                        <p class="mb-1">{{ $employee->mobile ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">PAN Number</small>
                        <p class="mb-1"><strong>{{ $employee->pan_number }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics & Details --}}
    <div class="col-lg-8">
        {{-- Quick Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-1">{{ $statistics['total_registrations'] }}</h4>
                        <small>Registrations</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-1">{{ $statistics['approved_registrations'] }}</h4>
                        <small>Approved</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-1">{{ $statistics['pending_registrations'] }}</h4>
                        <small>Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-1">{{ $statistics['total_certificates'] }}</h4>
                        <small>Certificates</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal Information --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small">Gender</label>
                            <p class="mb-0">{{ ucfirst($employee->gender) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <p class="mb-0">{{ $employee->date_of_birth->format('d M Y') }} ({{ $employee->age }} years)</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Blood Group</label>
                            <p class="mb-0">{{ $employee->blood_group ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small">Sports Category</label>
                            <p class="mb-0">{{ $employee->sports_category ?? 'Not Specified' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Medical Conditions</label>
                            <p class="mb-0">{{ $employee->medical_conditions ?? 'None' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Region / Airport</label>
                            <p class="mb-0">
                                {{ $employee->region->name ?? 'N/A' }} /
                                {{ $employee->airport->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Registrations --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-check me-2"></i>Event Registrations
                </h5>
                <span class="badge bg-primary">{{ $statistics['total_registrations'] }} Total</span>
            </div>
            <div class="card-body">
                @if($employee->eventRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->eventRegistrations->take(10) as $registration)
                                <tr>
                                    <td>
                                        <strong>{{ $registration->event->event_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $registration->event->event_code ?? '' }}</small>
                                    </td>
                                    <td>{{ $registration->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $registration->status == 'approved' ? 'success' : ($registration->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted my-4">No event registrations found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
