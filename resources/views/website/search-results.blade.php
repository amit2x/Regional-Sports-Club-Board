@extends('layouts.public')

@section('title', 'Search Results - RSCB')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Search Results for "{{ $query }}"</h2>

        {{-- Events Results --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Events ({{ $events->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($events as $event)
                    <div class="mb-3 p-3 border rounded">
                        <h6><a href="{{ route('website.event-details', $event->event_code) }}">{{ $event->event_name }}</a></h6>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }} |
                            <i class="bi bi-calendar me-1"></i>{{ $event->start_date->format('d M Y') }}
                        </p>
                    </div>
                @empty
                    <p class="text-muted">No events found.</p>
                @endforelse
            </div>
        </div>

        {{-- Announcements Results --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Announcements ({{ $announcements->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($announcements as $announcement)
                    <div class="mb-3 p-3 border rounded">
                        <h6><a href="{{ route('website.announcement-details', $announcement->id) }}">{{ $announcement->title }}</a></h6>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar me-1"></i>{{ $announcement->created_at->format('d M Y') }}
                        </p>
                    </div>
                @empty
                    <p class="text-muted">No announcements found.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
