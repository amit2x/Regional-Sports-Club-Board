{{-- resources/views/website/home.blade.php --}}
@extends('layouts.public')

@section('title', 'Regional Sports Control Board - Home')

@section('content')
{{-- Hero Section --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">Regional Sports Control Board</h1>
                <p class="hero-subtitle">
                    Empowering airport employees through sports and fitness.
                    Participate, compete, and excel in regional sports events.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('website.events') }}" class="btn btn-light btn-lg rounded-pill px-4">
                        <i class="bi bi-calendar-event me-2"></i>View Events
                    </a>
                    <a href="{{ route('employee.login') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="bi bi-person-check me-2"></i>Register Now
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div class="hero-image-fallback">
                    <i class="bi bi-trophy-fill" style="font-size: 120px; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Statistics Section --}}
<section class="py-5" style="margin-top: -50px; position: relative; z-index: 1;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-number" data-count="{{ $statistics['total_events'] }}">0</div>
                    <div class="text-muted">Events Organized</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-number" data-count="{{ $statistics['total_participants'] }}">0</div>
                    <div class="text-muted">Total Participants</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon">
                        <i class="bi bi-airplane"></i>
                    </div>
                    <div class="stat-number" data-count="{{ $statistics['total_airports'] }}">0</div>
                    <div class="text-muted">Airports Covered</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div class="stat-number" data-count="{{ $statistics['ongoing_events'] }}">0</div>
                    <div class="text-muted">Ongoing Events</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Upcoming Events --}}
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Upcoming Events</h2>

        <div class="row g-4">
            @forelse($upcomingEvents as $event)
                <div class="col-lg-4 col-md-6">
                    <div class="card event-card shadow-sm h-100">
                        @if($event->banner_image)
                            <img src="{{ asset('storage/' . $event->banner_image) }}"
                                 alt="{{ $event->event_name }}"
                                 class="event-banner"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="event-banner-placeholder" style="display: none;">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        @else
                            <div class="event-banner-placeholder">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">
                                {{ ucwords(str_replace('_', ' ', $event->event_type)) }}
                            </span>
                            <h5 class="card-title">{{ $event->event_name }}</h5>
                            <p class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}
                            </p>
                            <p class="text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                            </p>
                            <p class="text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                Last Date: {{ $event->registration_last_date->format('d M Y') }}
                            </p>
                            <a href="{{ route('website.event-details', $event->event_code) }}"
                               class="btn btn-outline-primary btn-sm stretched-link">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 64px;"></i>
                    <p class="text-muted mt-3">No upcoming events at the moment. Check back later!</p>
                </div>
            @endforelse
        </div>

        @if($upcomingEvents->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('website.events') }}" class="btn btn-primary rounded-pill px-5">
                    View All Events <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Announcements & Winners --}}
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <h3 class="mb-4">
                    <i class="bi bi-megaphone text-primary me-2"></i>Latest Announcements
                </h3>
                @forelse($latestAnnouncements as $announcement)
                    <a href="{{ route('website.announcement-details', $announcement->id) }}"
                       class="text-decoration-none">
                        <div class="announcement-item priority-{{ $announcement->priority }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 text-dark">{{ $announcement->title }}</h6>
                                    <p class="mb-0 text-muted small">
                                        {{ \Str::limit(strip_tags($announcement->content), 150) }}
                                    </p>
                                </div>
                                <span class="badge bg-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'info') }} ms-2">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-megaphone text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-2">No announcements yet.</p>
                    </div>
                @endforelse

                <a href="{{ route('website.announcements') }}" class="btn btn-outline-primary mt-3">
                    <i class="bi bi-arrow-right me-1"></i>View All Announcements
                </a>
            </div>

            <div class="col-lg-5">
                <h3 class="mb-4">
                    <i class="bi bi-trophy text-warning me-2"></i>Recent Winners
                </h3>
                @forelse($recentWinners as $winner)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $winner->employee->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $winner->event->event_name ?? 'N/A' }}</small>
                                    <br>
                                    <span class="badge bg-warning text-dark mt-1">{{ $winner->position ?? 'Winner' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-trophy text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-2">No winners announced yet.</p>
                    </div>
                @endforelse

                <a href="{{ route('website.winners') }}" class="btn btn-outline-warning mt-3">
                    <i class="bi bi-arrow-right me-1"></i>View All Winners
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Gallery Section --}}
@if($gallery->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Sports Gallery</h2>

        <div class="row g-3">
            @foreach($gallery as $image)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="position-relative overflow-hidden rounded-3" style="height: 200px;">
                        <img src="{{ asset('storage/' . $image->thumbnail_path) }}"
                             alt="{{ $image->title }}"
                             class="w-100 h-100 hover-zoom"
                             style="object-fit: cover;"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><rect fill=%22%23667eea%22 width=%22200%22 height=%22200%22/><text fill=%22white%22 x=%22100%22 y=%22110%22 text-anchor=%22middle%22>RSCB</text></svg>'">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('website.gallery') }}" class="btn btn-primary rounded-pill px-5">
                <i class="bi bi-images me-2"></i>View Full Gallery
            </a>
        </div>
    </div>
</section>
@endif
@endsection
