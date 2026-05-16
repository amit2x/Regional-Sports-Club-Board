@extends('layouts.employee')

@section('title', 'Employee Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['total_registrations'] }}</h3>
                <small>Total Registrations</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['approved_registrations'] }}</h3>
                <small>Approved</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['pending_registrations'] }}</h3>
                <small>Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['rejected_registrations'] }}</h3>
                <small>Rejected</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Events</h5>
            </div>
            <div class="card-body">
                @foreach($upcomingEvents as $event)
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                    <div>
                        <h6 class="mb-1">{{ $event->event_name }}</h6>
                        <small class="text-muted">
                            <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }} |
                            <i class="bi bi-calendar me-1"></i>{{ $event->start_date->format('d M Y') }}
                        </small>
                    </div>
                    <a href="{{ route('employee.events.register', $event->id) }}" class="btn btn-primary btn-sm">
                        Register
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Registrations</h5>
            </div>
            <div class="card-body">
                @foreach($myRegistrations as $reg)
                <div class="mb-2 p-2 border-bottom">
                    <small class="fw-bold">{{ $reg->event->event_name ?? 'N/A' }}</small>
                    <br>
                    <span class="badge bg-{{ $reg->status == 'approved' ? 'success' : ($reg->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($reg->status) }}
                    </span>
                    <small class="text-muted">{{ $reg->created_at->diffForHumans() }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
