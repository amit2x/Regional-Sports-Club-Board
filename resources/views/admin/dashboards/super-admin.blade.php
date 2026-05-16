@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-speedometer2 me-2"></i>Super Admin Dashboard
    </h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

{{-- Statistics Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-gradient-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Regions</h6>
                        <h3 class="text-white mb-0">{{ $stats['total_regions'] }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-gradient-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Airports</h6>
                        <h3 class="text-white mb-0">{{ $stats['total_airports'] }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-airplane"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-gradient-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Employees</h6>
                        <h3 class="text-white mb-0">{{ $stats['total_employees'] }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card bg-gradient-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Active Events</h6>
                        <h3 class="text-white mb-0">{{ $stats['active_events'] }}</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- More Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar bg-light-primary rounded-circle p-3">
                            <i class="bi bi-clipboard-check text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Registrations</h6>
                        <h4 class="mb-0">{{ $stats['total_registrations'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar bg-light-warning rounded-circle p-3">
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Pending Approvals</h6>
                        <h4 class="mb-0">{{ $stats['pending_approvals'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar bg-light-success rounded-circle p-3">
                            <i class="bi bi-file-earmark-check text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Verified Documents</h6>
                        <h4 class="mb-0">{{ $stats['verified_documents'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar bg-light-danger rounded-circle p-3">
                            <i class="bi bi-trophy text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Events</h6>
                        <h4 class="mb-0">{{ $stats['total_events'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Monthly Registration Trends</h5>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary">2024</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="registrationChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Gender Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Region-wise Performance & Recent Activities --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Region-wise Performance</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Region</th>
                                <th>Airports</th>
                                <th>Events</th>
                                <th>Registrations</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regionStats as $region)
                            <tr>
                                <td>
                                    <strong>{{ $region->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $region->code }}</small>
                                </td>
                                <td>{{ $region->airports_count }}</td>
                                <td>{{ $region->events_count }}</td>
                                <td>{{ $region->events->sum('registrations_count') }}</td>
                                <td>
                                    @php
                                        $performance = $region->events_count > 0
                                            ? ($region->events->sum('registrations_count') / $region->events_count)
                                            : 0;
                                    @endphp
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $performance > 50 ? 'success' : ($performance > 25 ? 'warning' : 'danger') }}"
                                             role="progressbar"
                                             style="width: {{ min($performance, 100) }}%">
                                        </div>
                                    </div>
                                    <small>{{ number_format($performance, 1) }} avg/event</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Activities</h5>
            </div>
            <div class="card-body">
                <div class="activity-timeline">
                    @foreach($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-dot bg-primary"></div>
                        <div class="activity-content">
                            <p class="mb-0">
                                <strong>{{ $activity->causer->name ?? 'System' }}</strong>
                                {{ $activity->description }}
                            </p>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Registration Chart
    const ctx1 = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRegistrations->pluck('month')) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($monthlyRegistrations->pluck('total')) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Gender Chart
    const ctx2 = document.getElementById('genderChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($genderStats->pluck('gender')->map(function($g) { return ucfirst($g); })) !!},
            datasets: [{
                data: {!! json_encode($genderStats->pluck('total')) !!},
                backgroundColor: ['#667eea', '#f093fb', '#4facfe']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
