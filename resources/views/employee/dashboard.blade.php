@extends('layouts.public')

@section('title', 'Dashboard')

@section('content')
<div class="page-content container">
    {{-- Welcome --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="avatar-circle bg-primary">
            {{ strtoupper(substr(auth()->guard('employee')->user()->name, 0, 2)) }}
        </div>
        <div>
            <h5 class="mb-0">Welcome, {{ auth()->guard('employee')->user()->name }}</h5>
            <small class="text-muted">{{ auth()->guard('employee')->user()->employee_id }}</small>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stat-grid">
        <div class="stat-item">
            <div class="stat-value">{{ $stats['total_registrations'] }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-item">
            <div class="stat-value text-success">{{ $stats['approved_registrations'] }}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-item">
            <div class="stat-value text-warning">{{ $stats['pending_registrations'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-item">
            <div class="stat-value text-danger">{{ $stats['rejected_registrations'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    {{-- Upcoming Events --}}
    <div class="employee-card">
        <div class="card-header">
            <i class="bi bi-calendar-event text-primary"></i> Upcoming Events
        </div>
        <div class="card-body p-0">
            @forelse($upcomingEvents as $event)
            <div class="p-3 border-bottom" onclick="window.location.href='{{ route('employee.events.register', $event->id) }}'" style="cursor:pointer;">
                <h6 class="mb-1">{{ $event->event_name }}</h6>
                <small class="text-muted">
                    <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }} |
                    <i class="bi bi-calendar me-1"></i>{{ $event->start_date->format('d M Y') }}
                </small>
                <a href="{{ route('employee.events.register', $event->id) }}" class="btn btn-sm btn-primary-mobile mt-2">Register</a>
            </div>
            @empty
            <p class="text-muted text-center py-4">No upcoming events</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Registrations --}}
    <div class="employee-card">
        <div class="card-header">
            <i class="bi bi-clipboard-check text-success"></i> My Registrations
        </div>
        <div class="card-body p-0">
            @forelse($myRegistrations->take(5) as $reg)
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <small class="fw-medium">{{ $reg->event->event_name ?? 'N/A' }}</small>
                    <br>
                    <small class="text-muted">{{ $reg->registration_number }}</small>
                </div>
                <span class="status-badge bg-{{ $reg->status === 'approved' ? 'success' : ($reg->status === 'pending' ? 'warning' : ($reg->status === 'rejected' ? 'danger' : 'secondary')) }} text-white">
                    {{ ucfirst($reg->status) }}
                </span>
            </div>
            @empty
            <p class="text-muted text-center py-4">No registrations yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
