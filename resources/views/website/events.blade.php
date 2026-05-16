{{-- resources/views/website/events.blade.php --}}
@extends('layouts.public')

@section('title', 'Events - RSCB')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Upcoming Events</h2>

        {{-- Filters --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <select class="form-select" id="eventTypeFilter">
                    <option value="">All Event Types</option>
                    @foreach($eventTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('event_type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="regionFilter">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchInput" placeholder="Search events..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-lg-4 col-md-6">
                    <div class="card event-card shadow-sm h-100">
                        @if($event->banner_image)
                            <img src="{{ asset('storage/' . $event->banner_image) }}"
                                 alt="{{ $event->event_name }}"
                                 class="event-banner"
                                 onerror="this.parentElement.innerHTML='<div class=\'event-banner-placeholder\'><i class=\'bi bi-calendar-event\'></i></div>'">
                        @else
                            <div class="event-banner-placeholder">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ ucwords(str_replace('_', ' ', $event->event_type)) }}</span>
                            <h5 class="card-title">{{ $event->event_name }}</h5>
                            <p class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}<br>
                                <i class="bi bi-calendar me-1"></i>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}<br>
                                <i class="bi bi-clock me-1"></i>Register by: {{ $event->registration_last_date->format('d M Y') }}
                            </p>
                            <a href="{{ route('website.event-details', $event->event_code) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 64px;"></i>
                    <p class="text-muted mt-3">No events found.</p>
                </div>
            @endforelse
        </div>

        {{ $events->links() }}
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('eventTypeFilter').addEventListener('change', applyFilters);
    document.getElementById('regionFilter').addEventListener('change', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyFilters();
    });

    function applyFilters() {
        const type = document.getElementById('eventTypeFilter').value;
        const region = document.getElementById('regionFilter').value;
        const search = document.getElementById('searchInput').value;

        let url = '{{ route("website.events") }}?';
        if (type) url += 'event_type=' + type + '&';
        if (region) url += 'region_id=' + region + '&';
        if (search) url += 'search=' + search + '&';

        window.location.href = url;
    }
</script>
@endpush
