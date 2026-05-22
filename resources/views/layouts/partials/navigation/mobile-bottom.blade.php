{{-- resources/views/layouts/partials/navigation/mobile-bottom.blade.php --}}
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <ul class="nav-list">
        <li class="nav-item">
            <a class="nav-link-bottom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                <i class="bi bi-house-heart"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link-bottom {{ request()->routeIs('website.events*') ? 'active' : '' }}" href="{{ route('website.events') }}">
                <i class="bi bi-calendar-event"></i>
                <span>Events</span>
            </a>
        </li>

        @auth('employee')
        <li class="nav-item">
            <a class="nav-link-bottom {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @else
        <li class="nav-item">
            <a class="nav-link-bottom" href="{{ route('employee.login') }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login</span>
            </a>
        </li>
        @endauth

        <li class="nav-item">
            <a class="nav-link-bottom {{ request()->routeIs('employee.registrations*') ? 'active' : '' }}"
               href="{{ auth('employee')->check() ? route('employee.registrations.index') : route('employee.login') }}">
                <i class="bi bi-clipboard-check"></i>
                <span>Registrations</span>
                @auth('employee')
                    @php
                        $pendingCount = \App\Models\EventRegistration::where('employee_id', auth()->guard('employee')->id())
                            ->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge-count">{{ $pendingCount }}</span>
                    @endif
                @endauth
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link-bottom" href="#" id="mobileMenuBtn">
                <i class="bi bi-list"></i>
                <span>Menu</span>
            </a>
        </li>
    </ul>
</nav>
