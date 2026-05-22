{{-- resources/views/employee/registrations/index.blade.php --}}
@extends('layouts.public')

@section('title', 'My Registrations')

@section('content')
<div class="page-content  container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">My Registrations</h5>
        <a href="{{ route('employee.events.available') }}" class="btn btn-sm btn-primary-mobile">
            <i class="bi bi-plus-lg me-1"></i>New
        </a>
    </div>

    @forelse($registrations as $reg)
    <div class="employee-card" onclick="window.location.href='{{ route('employee.registrations.show', $reg->id) }}'" style="cursor:pointer;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $reg->event->event_name ?? 'N/A' }}</h6>
                    <small class="text-muted">{{ $reg->registration_number }}</small>
                    <br>
                    <small class="text-muted">{{ $reg->created_at->format('d M Y') }}</small>
                </div>
                <span class="status-badge text-white bg-{{ $reg->status === 'approved' ? 'success' : ($reg->status === 'pending' ? 'warning' : ($reg->status === 'rejected' ? 'danger' : 'secondary')) }}">
                    {{ ucfirst($reg->status) }}
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-clipboard-x text-muted" style="font-size: 64px;"></i>
        <p class="text-muted mt-3">No registrations yet.</p>
        <a href="{{ route('employee.events.available') }}" class="btn btn-primary-mobile mt-2">Browse Events</a>
    </div>
    @endforelse
</div>
@endsection
