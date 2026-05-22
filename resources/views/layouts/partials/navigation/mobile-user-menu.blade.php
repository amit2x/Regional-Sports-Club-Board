{{-- resources/views/layouts/partials/navigation/mobile-user-menu.blade.php --}}
<hr class="my-2 border-secondary opacity-25">
<div class="px-3 py-1">
    <small class="text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:1px;">My Account</small>
</div>
<a class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a class="nav-link {{ request()->routeIs('employee.events*') ? 'active' : '' }}" href="{{ route('employee.events.available') }}">
    <i class="bi bi-calendar-check"></i> My Events
</a>
<a class="nav-link {{ request()->routeIs('employee.registrations*') ? 'active' : '' }}" href="{{ route('employee.registrations.index') }}">
    <i class="bi bi-clipboard-check"></i> Registrations
</a>
<a class="nav-link" href="{{ route('employee.profile.show') }}">
    <i class="bi bi-person"></i> My Profile
</a>
<a class="nav-link" href="{{ route('employee.notifications.index') }}">
    <i class="bi bi-bell"></i> Notifications
</a>
<hr class="my-2 border-secondary opacity-25">
<form action="{{ route('employee.logout') }}" method="POST" class="px-3">
    @csrf
    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
    </button>
</form>
