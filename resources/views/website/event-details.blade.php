@extends('layouts.public')

@section('title', $event->event_name . ' - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.events') }}">Events</a></li>
                <li class="breadcrumb-item active">{{ $event->event_name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Event Banner --}}
                <div class="mb-4 rounded-3 overflow-hidden" style="max-height: 400px;">
                    @if($event->banner_image)
                        <img src="{{ asset('storage/' . $event->banner_image) }}"
                             alt="{{ $event->event_name }}"
                             class="w-100"
                             style="object-fit: cover;">
                    @else
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="bi bi-calendar-event" style="font-size: 80px;"></i>
                        </div>
                    @endif
                </div>

                {{-- Event Info --}}
                <h2>{{ $event->event_name }}</h2>
                <p class="text-muted">Event Code: {{ $event->event_code }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Event Type</small>
                            <p class="mb-0 fw-bold">{{ ucwords(str_replace('_', ' ', $event->event_type)) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Participation Type</small>
                            <p class="mb-0 fw-bold">{{ ucfirst($event->participation_type) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Venue</small>
                            <p class="mb-0 fw-bold"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Region</small>
                            <p class="mb-0 fw-bold">{{ $event->region->name ?? 'All Regions' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Important Dates</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Event Start</small>
                                <p class="fw-bold">{{ $event->start_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Event End</small>
                                <p class="fw-bold">{{ $event->end_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Registration Deadline</small>
                                <p class="fw-bold text-danger">{{ $event->registration_last_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($event->description)
                <div class="mb-4">
                    <h5>Description</h5>
                    <p>{{ $event->description }}</p>
                </div>
                @endif

                {{-- Rules --}}
                @if($event->rules_regulations)
                <div class="mb-4">
                    <h5>Rules & Regulations</h5>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($event->rules_regulations)) !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                {{-- Registration Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Registration</h5>
                        <p>
                            <span class="badge bg-success">{{ $approvedCount }} Approved</span>
                            @if($event->max_participants)
                                <span class="badge bg-info">Max {{ $event->max_participants }}</span>
                            @endif
                        </p>
                        <p>Total Registrations: {{ $registrationCount }}</p>

                        @if($event->isRegistrationOpen)
                            <a href="{{ route('employee.login') }}" class="btn btn-primary w-100">
                                <i class="bi bi-pencil-square me-1"></i>Register Now
                            </a>
                            <small class="text-muted d-block mt-2 text-center">
                                Login required to register
                            </small>
                        @else
                            <button class="btn btn-secondary w-100" disabled>Registration Closed</button>
                        @endif
                    </div>
                </div>

                {{-- Eligibility --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Eligibility</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-gender-ambiguous me-2"></i>{{ ucfirst($event->gender_eligibility) }}</li>
                            @if($event->min_age)
                                <li class="mb-2"><i class="bi bi-person me-2"></i>Min Age: {{ $event->min_age }} years</li>
                            @endif
                            @if($event->max_age)
                                <li class="mb-2"><i class="bi bi-person me-2"></i>Max Age: {{ $event->max_age }} years</li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Airport Info --}}
                @if($event->airport)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->airport->name }}</h5>
                        <p class="text-muted small">{{ $event->airport->code }}</p>
                        <p class="small"><i class="bi bi-geo-alt me-1"></i>{{ $event->airport->city }}, {{ $event->airport->state }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Related Events --}}
        @if($relatedEvents->count() > 0)
        <div class="mt-5">
            <h4>Related Events</h4>
            <div class="row g-3 mt-3">
                @foreach($relatedEvents as $related)
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6>{{ $related->event_name }}</h6>
                                <p class="text-muted small mb-2">{{ $related->start_date->format('d M Y') }}</p>
                                <a href="{{ route('website.event-details', $related->event_code) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
