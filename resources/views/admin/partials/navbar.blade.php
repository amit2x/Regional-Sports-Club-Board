<nav class="navbar navbar-expand-lg top-navbar">
    <div class="container-fluid">
        <button class="btn btn-link sidebar-toggler" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            {{-- Notifications --}}
            <div class="dropdown me-3">
                <button class="btn btn-link notification-btn" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    @if($unreadNotifications = auth()->guard('employee')->user()->unreadNotifications->count())
                        <span class="badge bg-danger notification-badge">{{ $unreadNotifications }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-menu">
                    <div class="notification-header">
                        <h6>Notifications</h6>
                        <a href="#" class="text-decoration-none">Mark all as read</a>
                    </div>
                    <div class="notification-body">
                        @forelse(auth()->guard('employee')->user()->notifications->take(5) as $notification)
                            <a href="#" class="notification-item">
                                <div class="notification-icon">
                                    <i class="bi bi-calendar-check text-primary"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="mb-0">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-muted my-3">No new notifications</p>
                        @endforelse
                    </div>
                    <div class="notification-footer">
                        <a href="{{ route('notifications.index') }}" class="text-decoration-none">View All</a>
                    </div>
                </div>
            </div>

            {{-- User Profile --}}
            <div class="dropdown">
                <button class="btn btn-link user-profile-btn" data-bs-toggle="dropdown">
                    <img src="{{ auth()->guard('employee')->user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                         alt="Profile"
                         class="rounded-circle"
                         width="40"
                         height="40">
                    <span class="ms-2 d-none d-md-inline">
                        {{ auth()->guard('employee')->user()->name }}
                        <i class="bi bi-chevron-down ms-1"></i>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header">
                        <strong>{{ auth()->guard('employee')->user()->name }}</strong>
                        <br>
                        <small class="text-muted">{{ auth()->guard('employee')->user()->employee_id }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                    <a href="{{ route('employee.password.change') }}" class="dropdown-item">
                        <i class="bi bi-key"></i> Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('employee.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
