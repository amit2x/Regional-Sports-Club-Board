@extends('layouts.admin')

@section('title', 'Event Details')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ $event->event_name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                <li class="breadcrumb-item active">{{ $event->event_code }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @can('edit_events')
        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endcan
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        @if($event->banner_image)
        <div class="card border-0 shadow-sm mb-4">
            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Banner" class="card-img-top" style="max-height: 300px; object-fit: cover;">
        </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Event Information</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted">Event Code</small><p>{{ $event->event_code }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Type</small><p>{{ ucwords(str_replace('_', ' ', $event->event_type)) }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Venue</small><p>{{ $event->venue }}</p></div>
                    <div class="col-md-6"><small class="text-muted">Participation</small><p>{{ ucfirst($event->participation_type) }}</p></div>
                    <div class="col-md-4"><small class="text-muted">Start Date</small><p>{{ $event->start_date->format('d M Y H:i') }}</p></div>
                    <div class="col-md-4"><small class="text-muted">End Date</small><p>{{ $event->end_date->format('d M Y H:i') }}</p></div>
                    <div class="col-md-4"><small class="text-muted">Registration Deadline</small><p>{{ $event->registration_last_date->format('d M Y H:i') }}</p></div>
                </div>
            </div>
        </div>

        @if($event->description)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Description</h5></div>
            <div class="card-body"><p>{{ $event->description }}</p></div>
        </div>
        @endif

        @if($event->rules_regulations)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Rules & Regulations</h5></div>
            <div class="card-body">{!! nl2br(e($event->rules_regulations)) !!}</div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Statistics</h5></div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6"><h3 class="text-primary">{{ $statistics['total_registrations'] }}</h3><small>Total</small></div>
                    <div class="col-6"><h3 class="text-success">{{ $statistics['approved'] }}</h3><small>Approved</small></div>
                    <div class="col-6"><h3 class="text-warning">{{ $statistics['pending'] }}</h3><small>Pending</small></div>
                    <div class="col-6"><h3 class="text-danger">{{ $statistics['rejected'] }}</h3><small>Rejected</small></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Status</h5></div>
            <div class="card-body text-center">
                <span class="badge bg-{{ $event->status === 'published' ? 'success' : ($event->status === 'draft' ? 'secondary' : 'danger') }} fs-5 px-4 py-2">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
