{{-- resources/views/layouts/partials/navigation/user-menu.blade.php --}}
<li class="nav-item d-flex align-items-center g-1">
    @php
        $user = auth()->guard('employee')->user();
        $dashboardRoute = $user->hasRole('super_admin') ? route('admin.dashboard') :
            ($user->hasRole('regional_sports_secretary') ? route('admin.regional.dashboard') :
            ($user->hasRole('airport_sports_secretary') ? route('admin.airport.dashboard') : route('employee.dashboard')));
    @endphp

    <a class="btn btn-outline-primary btn-sm px-2 py-1 text-nowrap me-1" href="{{ $dashboardRoute }}">
        <i class="bi bi-speedometer2 me-1"></i> Dashboard
    </a>

    <a class="btn btn-sm btn-outline-warning px-2 py-1 text-nowrap me-1 {{ request()->routeIs('employee.events*') ? 'active' : '' }}" href="{{ route('employee.events.available') }}">
        <i class="bi bi-calendar-check me-1"></i> My Events
    </a>

    <a class="btn btn-sm btn-outline-info px-2 py-1 text-nowrap {{ request()->routeIs('employee.registrations*') ? 'active' : '' }}" href="{{ route('employee.registrations.index') }}">
        <i class="bi bi-clipboard-check me-1"></i> Registrations
    </a>
</li>

<li class="nav-item dropdown ms-2">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if($user->profile_photo)
            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="user-avatar-nav me-2" alt="{{ $user->name }}">
        @else
            <div class="user-avatar-nav bg-primary d-flex align-items-center justify-content-center text-white fw-bold me-2" style="font-size:14px;">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
        @endif
        <span class="d-none d-md-inline">{{ $user->name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
        <li class="px-3 py-2">
            <small class="text-muted">{{ $user->employee_id }}</small>
            <strong class="d-block">{{ $user->email }}</strong>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('employee.profile.show') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
        <li><a class="dropdown-item" href="{{ route('employee.notifications.index') }}"><i class="bi bi-bell me-2"></i> Notifications</a></li>
        <li><a class="dropdown-item" href="{{ route('employee.password.change') }}"><i class="bi bi-key me-2"></i> Change Password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form action="{{ route('employee.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                </button>
            </form>
        </li>
    </ul>
</li>
