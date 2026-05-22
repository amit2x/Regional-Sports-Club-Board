{{-- resources/views/layouts/partials/navigation/mobile-sidebar.blade.php --}}
<div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

<div class="mobile-sidebar" id="mobileSidebar">
    <div class="mobile-sidebar-header">
        <span class="sidebar-brand">RSCB</span>
        <button class="btn-close btn-close-white" id="mobileSidebarClose" aria-label="Close menu"></button>
    </div>

    <div class="py-2">
        {{-- Main Navigation --}}
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
            <i class="bi bi-house-heart"></i> Home
        </a>
        <a class="nav-link {{ request()->routeIs('website.events*') ? 'active' : '' }}" href="{{ route('website.events') }}">
            <i class="bi bi-calendar-event"></i> Events
        </a>
        <a class="nav-link {{ request()->routeIs('website.announcements*') ? 'active' : '' }}" href="{{ route('website.announcements') }}">
            <i class="bi bi-megaphone"></i> Announcements
        </a>
        <a class="nav-link {{ request()->routeIs('website.gallery*') ? 'active' : '' }}" href="{{ route('website.gallery') }}">
            <i class="bi bi-images"></i> Gallery
        </a>
        <a class="nav-link {{ request()->routeIs('website.winners*') ? 'active' : '' }}" href="{{ route('website.winners') }}">
            <i class="bi bi-trophy"></i> Winners
        </a>
        <a class="nav-link {{ request()->routeIs('website.contact*') ? 'active' : '' }}" href="{{ route('website.contact') }}">
            <i class="bi bi-envelope"></i> Contact
        </a>

        @auth('employee')
            @include('layouts.partials.navigation.mobile-user-menu')
        @else
            @include('layouts.partials.navigation.mobile-guest-menu')
        @endauth

        {{-- Quick Links --}}
        <hr class="my-2 border-secondary opacity-25">
        <div class="px-3 py-1">
            <small class="text-muted text-uppercase" style="font-size:0.7rem; letter-spacing:1px;">Quick Links</small>
        </div>
        <a class="nav-link" href="{{ route('website.faq') }}"><i class="bi bi-question-circle"></i> FAQ</a>
        <a class="nav-link" href="{{ route('website.about') }}"><i class="bi bi-info-circle"></i> About</a>
        <a class="nav-link" href="{{ route('website.help') }}"><i class="bi bi-life-preserver"></i> Help</a>
        <a class="nav-link" href="{{ route('website.privacy') }}"><i class="bi bi-shield-check"></i> Privacy</a>
        <a class="nav-link" href="{{ route('website.terms') }}"><i class="bi bi-file-text"></i> Terms</a>
    </div>

    <div class="mobile-sidebar-footer">
        <small>&copy; {{ date('Y') }} RSCB. All rights reserved.</small>
    </div>
</div>
