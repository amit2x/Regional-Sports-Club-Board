{{-- resources/views/layouts/partials/navigation/desktop.blade.php --}}
<nav class="navbar navbar-desktop navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-white.png') }}" alt="RSCB" onerror="this.style.display='none';">
            <span>RSCB</span>
        </a>

        <button class="navbar-toggler border-0" type="button" id="mobileSidebarToggle" aria-label="Toggle menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-house-heart me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('website.events*') ? 'active' : '' }}" href="{{ route('website.events') }}">
                        <i class="bi bi-calendar-event me-1"></i> Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('website.announcements*') ? 'active' : '' }}" href="{{ route('website.announcements') }}">
                        <i class="bi bi-megaphone me-1"></i> Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('website.gallery*') ? 'active' : '' }}" href="{{ route('website.gallery') }}">
                        <i class="bi bi-images me-1"></i> Gallery
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('website.winners*') ? 'active' : '' }}" href="{{ route('website.winners') }}">
                        <i class="bi bi-trophy me-1"></i> Winners
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center">
                @auth('employee')
                    @include('layouts.partials.navigation.user-menu')
                @else
                    @include('layouts.partials.navigation.guest-menu')
                @endauth
            </ul>
        </div>
    </div>
</nav>
