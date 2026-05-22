@extends('layouts.public')

@section('title', 'Available Events')

@section('content')
<div class="page-content container">
    <h5 class="mb-3">Available Events</h5>

    @forelse($eligibleEvents as $event)
    <div class="event-card-mobile">
        @if($event->banner_image)
            <div class="event-banner">
                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->event_name }}">
            </div>
        @else
            <div class="event-banner">
                <i class="bi bi-calendar-event"></i>
            </div>
        @endif
        <div class="event-info">
            <span class="status-badge bg-primary text-white mb-2 d-inline-block">
                {{ ucwords(str_replace('_', ' ', $event->event_type)) }}
            </span>
            <h6>{{ $event->event_name }}</h6>
            <small class="text-muted">
                <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}<br>
                <i class="bi bi-calendar me-1"></i>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}<br>
                <i class="bi bi-clock me-1"></i>Last date: {{ $event->registration_last_date->format('d M Y') }}
            </small>
            <div class="mt-3 d-flex gap-2">
                @if(in_array($event->id, $registeredEventIds))
                    <button class="btn btn-secondary btn-mobile flex-fill" disabled>Registered</button>
                @elseif($event->isRegistrationOpen)
                    <a href="{{ route('employee.events.register', $event->id) }}" class="btn btn-primary-mobile btn-mobile flex-fill">
                        <i class="bi bi-pencil-square me-1"></i>Register
                    </a>
                @else
                    <button class="btn btn-secondary btn-mobile flex-fill" disabled>Closed</button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-calendar-x text-muted" style="font-size: 64px;"></i>
        <p class="text-muted mt-3">No eligible events available right now.</p>
    </div>
    @endforelse
</div>
@endsection
