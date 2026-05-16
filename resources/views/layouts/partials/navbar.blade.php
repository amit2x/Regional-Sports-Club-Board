{{-- resources/views/admin/partials/navbar.blade.php --}}
<nav class="top-navbar">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggler" id="sidebarToggle" title="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button>

            {{-- Search Bar --}}
            <div class="d-none d-md-flex align-items-center bg-light rounded-3 px-3 py-2" style="min-width: 300px;">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" class="border-0 bg-transparent" placeholder="Search..." style="outline: none; width: 100%;">
                <kbd class="bg-white text-muted px-2 py-1 rounded small">⌘K</kbd>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            {{-- Notifications --}}
            <div class="dropdown">
                <button class="notification-btn" data-bs-toggle="dropdown" title="Notifications">
                    <i class="bi bi-bell-fill"></i>
                    @php
                        $unreadCount = auth()->guard('employee')->check() ?
                            auth()->guard('employee')->user()->unreadNotifications->count() : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="notification-badge"></span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-menu">
                    <div class="notification-header">
                        <h6>Notifications</h6>
                        <a href="#" class="mark-all-read text-decoration-none small text-primary">Mark all read</a>
                    </div>
                    <div class="notification-body" style="max-height: 350px; overflow-y: auto;">
                        @if(auth()->guard('employee')->check())
                            @forelse(auth()->guard('employee')->user()->notifications->take(8) as $notification)
                                <a href="{{ route('notifications.click', $notification->id) }}"
                                   class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                                   data-id="{{ $notification->id }}">
                                    <div class="notification-icon bg-{{ $notification->data['type'] === 'announcement' ? 'warning' : 'primary' }} bg-opacity-10">
                                        <i class="bi bi-{{ $notification->data['type'] === 'announcement' ? 'megaphone' : 'calendar-check' }} text-{{ $notification->data['type'] === 'announcement' ? 'warning' : 'primary' }}"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="mb-0 fw-medium">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-bell-slash text-muted" style="font-size: 40px;"></i>
                                    <p class="text-muted mt-2 mb-0">No notifications yet</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                    <div class="notification-footer">
                        <a href="{{ route('notifications.index') }}" class="text-decoration-none small fw-medium">
                            View All Notifications <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- User Profile --}}
            <div class="dropdown ms-2">
                <button class="user-profile-btn" data-bs-toggle="dropdown">
                    @if(auth()->guard('employee')->check() && auth()->guard('employee')->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->guard('employee')->user()->profile_photo) }}"
                             alt="Profile"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="user-avatar bg-primary text-white" style="display:none; align-items:center; justify-content:center; font-weight:600;">
                            {{ strtoupper(substr(auth()->guard('employee')->user()->name, 0, 2)) }}
                        </div>
                    @else
                        <div class="user-avatar bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                            {{ auth()->guard('employee')->check() ? strtoupper(substr(auth()->guard('employee')->user()->name, 0, 2)) : 'U' }}
                        </div>
                    @endif
                    <div class="user-info d-none d-md-block">
                        <div class="user-name">{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->name : 'Guest' }}</div>
                        <div class="user-role">
                            @if(auth()->guard('employee')->check())
                                @php $roles = auth()->guard('employee')->user()->getRoleNames(); @endphp
                                {{ $roles->isNotEmpty() ? ucwords(str_replace('_', ' ', $roles->first())) : 'Employee' }}
                            @endif
                        </div>
                    </div>
                    <i class="bi bi-chevron-down ms-1 d-none d-md-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="width: 240px;">
                    <div class="px-3 py-3 border-bottom">
                        <strong class="d-block">{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->name : 'Guest' }}</strong>
                        <small class="text-muted">{{ auth()->guard('employee')->check() ? auth()->guard('employee')->user()->email : '' }}</small>
                    </div>
                    <a href="{{ route('profile.show') }}" class="dropdown-item py-2">
                        <i class="bi bi-person me-2"></i>My Profile
                    </a>
                    <a href="{{ route('employee.password.change') }}" class="dropdown-item py-2">
                        <i class="bi bi-key me-2"></i>Change Password
                    </a>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item py-2">
                        <i class="bi bi-bell me-2"></i>Notifications
                        @if($unreadCount > 0)
                            <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('employee.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
