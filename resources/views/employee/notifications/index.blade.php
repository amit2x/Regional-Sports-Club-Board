{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.public')

@section('title', 'Notifications')

@section('content')
<div class="page-content container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Notifications</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="markAllRead()">Mark All Read</button>
    </div>

    @forelse($notifications as $notification)
    <div class="employee-card {{ is_null($notification->read_at) ? 'border-start border-4 border-primary' : '' }}"
         onclick="markRead('{{ $notification->id }}')" style="cursor:pointer;">
        <div class="card-body">
            <p class="mb-1 small">{{ $notification->data['message'] ?? 'Notification' }}</p>
            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-bell-slash text-muted" style="font-size: 64px;"></i>
        <p class="text-muted mt-3">No notifications</p>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    function markRead(id) {
        $.post('/notifications/' + id + '/read', { _token: '{{ csrf_token() }}' }, function() {
            location.reload();
        });
    }
    function markAllRead() {
        const button = event.target;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';

        fetch('{{ route("notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove highlight from all items
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-light');
                    item.querySelector('.unread-indicator')?.remove();
                });

                // Update badge
                const badge = document.querySelector('.badge.bg-danger');
                if (badge) badge.remove();

                // Update button
                const markAllBtn = document.querySelector('.btn-outline-primary');
                if (markAllBtn) markAllBtn.remove();

                // Show success
                toastr.success(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Failed to mark notifications as read');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-double me-1"></i>Mark All as Read';
        });
    }

    // Auto-load latest notifications count
    function updateUnreadCount() {
        fetch('{{ route("notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.badge.bg-danger');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                    }
                } else {
                    if (badge) badge.remove();
                }
            });
    }

    // Refresh count every 30 seconds
    setInterval(updateUnreadCount, 30000);
</script>
@endpush
