@extends('layouts.admin')

@section('title', 'Region Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $region->name }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.regions.index') }}">Regions</a></li>
            <li class="breadcrumb-item active">{{ $region->code }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h3>{{ $region->name }}</h3>
                <p class="text-muted">Code: {{ $region->code }}</p>
                <span class="badge bg-{{ $region->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($region->status) }}</span>
                <hr>
                <div class="row g-3">
                    <div class="col-6"><h4>{{ $region->airports_count }}</h4><small class="text-muted">Airports</small></div>
                    <div class="col-6"><h4>{{ $region->employees_count }}</h4><small class="text-muted">Employees</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Airports ({{ $airports->count() }})</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Code</th><th>Name</th><th>City</th><th>Employees</th></tr></thead>
                        <tbody>
                            @foreach($airports as $airport)
                            <tr>
                                <td>{{ $airport->code }}</td>
                                <td>{{ $airport->name }}</td>
                                <td>{{ $airport->city }}</td>
                                <td>{{ $airport->employees_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Recent Events</h5></div>
            <div class="card-body">
                @forelse($recentEvents as $event)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <p class="mb-0 fw-medium">{{ $event->event_name }}</p>
                        <small class="text-muted">{{ $event->event_code }}</small>
                    </div>
                    <span class="badge bg-info">{{ $event->status }}</span>
                </div>
                @empty
                <p class="text-muted">No events</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
