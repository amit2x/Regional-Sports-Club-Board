@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="page-header">
    <h1 class="page-title">Audit Logs</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Audit Logs</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="auditLogsTable">
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Subject</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $logs = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->paginate(50);
                    @endphp
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td>{{ $log->causer->name ?? 'System' }}</td>
                        <td><span class="badge bg-info">{{ $log->description }}</span></td>
                        <td>{{ class_basename($log->subject_type) ?? 'N/A' }} #{{ $log->subject_id }}</td>
                        <td>
                            @if($log->properties)
                                <small class="text-muted">{{ json_encode($log->properties) }}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
