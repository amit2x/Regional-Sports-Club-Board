@extends('layouts.public')

@section('title', 'Winners - RSCB')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Hall of Fame</h2>

        <div class="row g-4">
            @forelse($winners as $winner)
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px; font-size: 32px;">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>
                            </div>
                            <h5 class="card-title">{{ $winner->employee->name ?? 'N/A' }}</h5>
                            <p class="text-muted">{{ $winner->employee->department ?? '' }}</p>
                            <span class="badge bg-{{ $winner->position == '1st' ? 'gold' : ($winner->position == '2nd' ? 'silver' : 'warning') }} mb-3">
                                {{ $winner->position }} Place
                            </span>
                            <h6>{{ $winner->event->event_name ?? 'N/A' }}</h6>
                            <p class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>{{ $winner->event->venue ?? '' }}<br>
                                <i class="bi bi-calendar me-1"></i>{{ $winner->event->start_date ? $winner->event->start_date->format('d M Y') : '' }}
                            </p>
                            @if($winner->achievement)
                                <p class="small">{{ $winner->achievement }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-trophy text-muted" style="font-size: 64px;"></i>
                    <p class="text-muted mt-3">Winners will be announced after events completion.</p>
                </div>
            @endforelse
        </div>

        {{ $winners->links('pagination::bootstrap-5') }}
    </div>
</section>
@endsection
