{{-- resources/views/employee/registrations/show.blade.php --}}
@extends('layouts.public')

@section('title', 'Registration Details')

@section('content')
<div class="page-content container">
    <a href="{{ route('employee.registrations.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>

    <div class="employee-card">
        <div class="card-body text-center">
            <h6 class="mb-1">{{ $registration->registration_number }}</h6>
            <span class="status-badge text-white bg-{{ $registration->status === 'approved' ? 'success' : ($registration->status === 'pending' ? 'warning' : ($registration->status === 'rejected' ? 'danger' : 'secondary')) }}">
                {{ ucfirst($registration->status) }}
            </span>
        </div>
    </div>

    <div class="employee-card">
        <div class="card-header"><i class="bi bi-calendar-event me-2"></i>Event Details</div>
        <div class="card-body">
            <p class="fw-medium">{{ $registration->event->event_name ?? 'N/A' }}</p>
            <small class="text-muted">{{ $registration->event->venue ?? '' }} | {{ $registration->event->start_date ? $registration->event->start_date->format('d M Y') : '' }}</small>
        </div>
    </div>

    @if($registration->documents->count() > 0)
    <div class="employee-card">
        <div class="card-header"><i class="bi bi-file-earmark me-2"></i>Documents</div>
        <div class="card-body p-0">
            @foreach($registration->documents as $doc)
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <small>{{ $doc->document_name }}</small>
                <span class="status-badge bg-{{ $doc->verification_status === 'verified' ? 'success' : 'warning' }} text-white small">
                    {{ ucfirst($doc->verification_status) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
