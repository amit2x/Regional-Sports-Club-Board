@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-graph-up me-2"></i>Reports & Analytics
    </h1>
</div>

{{-- Report Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-people-fill text-primary" style="font-size: 48px;"></i>
                </div>
                <h5>Participation Report</h5>
                <p class="text-muted small">Region-wise, airport-wise participation details</p>
                <a href="#participationSection" class="btn btn-primary btn-sm">
                    <i class="bi bi-eye me-1"></i>View
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-calendar-event text-success" style="font-size: 48px;"></i>
                </div>
                <h5>Event Report</h5>
                <p class="text-muted small">Event-wise statistics and performance</p>
                <a href="#eventSection" class="btn btn-success btn-sm">
                    <i class="bi bi-eye me-1"></i>View
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-pie-chart text-warning" style="font-size: 48px;"></i>
                </div>
                <h5>Region Analytics</h5>
                <p class="text-muted small">Region-wise performance metrics</p>
                <a href="{{ route('admin.reports.region-analytics') }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-eye me-1"></i>View
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-gender-ambiguous text-info" style="font-size: 48px;"></i>
                </div>
                <h5>Gender Analytics</h5>
                <p class="text-muted small">Gender-wise participation analysis</p>
                <a href="{{ route('admin.reports.gender-analytics') }}" class="btn btn-info btn-sm">
                    <i class="bi bi-eye me-1"></i>View
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Participation Report Section --}}
<div id="participationSection" class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-people me-2"></i>Participation Report
        </h5>
    </div>
    <div class="card-body">
        <form id="participationReportForm" class="row g-3 mb-4">
            <div class="col-md-2">
                <label class="form-label">Region</label>
                <select name="region_id" class="form-select">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Airport</label>
                <select name="airport_id" class="form-select">
                    <option value="">All Airports</option>
                    @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control">
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-primary" onclick="generateParticipationReport()">
                    <i class="bi bi-search me-1"></i>Generate Report
                </button>
                <button type="button" class="btn btn-success" onclick="exportParticipationReport('excel')">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
                <button type="button" class="btn btn-danger" onclick="exportParticipationReport('pdf')">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
            </div>
        </form>

        <div id="participationResults">
            <div class="text-center text-muted py-5">
                <i class="bi bi-search" style="font-size: 48px;"></i>
                <p>Select filters and click "Generate Report" to view results</p>
            </div>
        </div>
    </div>
</div>

{{-- Event Report Section --}}
<div id="eventSection" class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-calendar-event me-2"></i>Event Report
        </h5>
    </div>
    <div class="card-body">
        <form id="eventReportForm" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Region</label>
                <select name="region_id" class="form-select">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Event Type</label>
                <select name="event_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="regional">Regional</option>
                    <option value="airport">Airport</option>
                    <option value="inter_airport">Inter-Airport</option>
                    <option value="annual_meet">Annual Meet</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control">
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-primary" onclick="generateEventReport()">
                    <i class="bi bi-search me-1"></i>Generate Report
                </button>
                <button type="button" class="btn btn-success" onclick="exportEventReport('excel')">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
                <button type="button" class="btn btn-danger" onclick="exportEventReport('pdf')">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
            </div>
        </form>

        <div id="eventResults">
            <div class="text-center text-muted py-5">
                <i class="bi bi-search" style="font-size: 48px;"></i>
                <p>Select filters and click "Generate Report" to view results</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateParticipationReport() {
    let formData = $('#participationReportForm').serialize();

    $.ajax({
        url: '{{ route("admin.reports.participation") }}',
        type: 'GET',
        data: formData,
        beforeSend: function() {
            $('#participationResults').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
        },
        success: function(response) {
            let statsHtml = '<div class="row g-3 mb-4">';
            statsHtml += `<div class="col-md-2"><div class="bg-light p-3 rounded text-center"><h4>${response.statistics.total}</h4><small>Total</small></div></div>`;
            statsHtml += `<div class="col-md-2"><div class="bg-success text-white p-3 rounded text-center"><h4>${response.statistics.approved}</h4><small>Approved</small></div></div>`;
            statsHtml += `<div class="col-md-2"><div class="bg-warning p-3 rounded text-center"><h4>${response.statistics.pending}</h4><small>Pending</small></div></div>`;
            statsHtml += `<div class="col-md-2"><div class="bg-danger text-white p-3 rounded text-center"><h4>${response.statistics.rejected}</h4><small>Rejected</small></div></div>`;
            statsHtml += `<div class="col-md-2"><div class="bg-info text-white p-3 rounded text-center"><h4>${response.statistics.male}</h4><small>Male</small></div></div>`;
            statsHtml += `<div class="col-md-2"><div class="bg-pink text-white p-3 rounded text-center"><h4>${response.statistics.female}</h4><small>Female</small></div></div>`;
            statsHtml += '</div>';

            $('#participationResults').html(statsHtml + response.html);
        },
        error: function() {
            $('#participationResults').html('<div class="alert alert-danger">Failed to generate report</div>');
        }
    });
}

function exportParticipationReport(type) {
    let formData = $('#participationReportForm').serialize() + '&export_type=' + type;
    window.location.href = '{{ route("admin.reports.participation") }}?' + formData;
}

function generateEventReport() {
    let formData = $('#eventReportForm').serialize();

    $.ajax({
        url: '{{ route("admin.reports.events") }}',
        type: 'GET',
        data: formData,
        beforeSend: function() {
            $('#eventResults').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
        },
        success: function(response) {
            let statsHtml = '<div class="row g-3 mb-4">';
            statsHtml += `<div class="col-md-3"><div class="bg-light p-3 rounded text-center"><h4>${response.statistics.total_events}</h4><small>Total Events</small></div></div>`;
            statsHtml += `<div class="col-md-3"><div class="bg-success text-white p-3 rounded text-center"><h4>${response.statistics.published_events}</h4><small>Published</small></div></div>`;
            statsHtml += `<div class="col-md-3"><div class="bg-info text-white p-3 rounded text-center"><h4>${response.statistics.total_registrations}</h4><small>Total Registrations</small></div></div>`;
            statsHtml += `<div class="col-md-3"><div class="bg-warning p-3 rounded text-center"><h4>${response.statistics.average_participation}</h4><small>Avg Participation</small></div></div>`;
            statsHtml += '</div>';

            $('#eventResults').html(statsHtml + response.html);
        },
        error: function() {
            $('#eventResults').html('<div class="alert alert-danger">Failed to generate report</div>');
        }
    });
}

function exportEventReport(type) {
    let formData = $('#eventReportForm').serialize() + '&export_type=' + type;
    window.location.href = '{{ route("admin.reports.events") }}?' + formData;
}
</script>
@endpush
