{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Notifications</h1>
    </div>
    <button class="btn btn-outline-primary" onclick="markAllRead()">
        <i class="bi bi-check-all me-1"></i>Mark All Read
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @forelse($notifications as $notification)
        <div class="d-flex gap-3 p-3 {{ is_null($notification->read_at) ? 'bg-light' : '' }} rounded-3 mb-2">
            <div class="bg-primary bg-opacity-10 rounded-circle p-2" style="width: 40px; height: 40px;">
                <i class="bi bi-bell text-primary"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1">{{ $notification->data['message'] ?? 'Notification' }}</p>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            @if(is_null($notification->read_at))
                <button class="btn btn-sm btn-link text-primary" onclick="markRead('{{ $notification->id }}')">Mark read</button>
            @endif
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-bell-slash text-muted" style="font-size: 48px;"></i>
            <p class="text-muted mt-3">No notifications</p>
        </div>
        @endforelse
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function markRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(() => location.reload());
}
function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(() => location.reload());
}
</script>
@endpush
