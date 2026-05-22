{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic SEO Meta Tags --}}
    <title>@yield('title', 'RSCB - Regional Sports Control Board')</title>
    <meta name="description" content="@yield('meta_description', 'Centralized sports management portal for Airports/Regional Offices. Announce events, participate, and manage registrations.')">
    <meta name="keywords" content="@yield('meta_keywords', 'RSCB, sports management, airport sports, employee sports, sports events, tournament registration')">
    <meta name="author" content="RSCB">
    <meta name="theme-color" content="#667eea">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'RSCB - Regional Sports Control Board')">
    <meta property="og:description" content="@yield('og_description', 'Centralized sports management portal for airport employees.')">
    <meta property="og:site_name" content="RSCB">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/icon-180.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons/icon-16.png') }}">
    <link rel="manifest" href="{{ route('manifest') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #764ba2;
            --body-font: 'Inter', sans-serif;
            --heading-font: 'Poppins', sans-serif;
            --bottom-nav-height: 68px;
            --safe-area-bottom: env(safe-area-inset-bottom, 0px);
            --topbar-height: 60px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--body-font);
            background: #f1f5f9;
            color: #1e293b;
            line-height: 1.7;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
            font-weight: 700;
            color: #0f172a;
        }

        /* ==========================================
           DESKTOP TOP NAVBAR
           ========================================== */
        .navbar-desktop {
            background: rgba(15, 23, 42, 0.97) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 10px 0;
            transition: all 0.3s;
            z-index: 1020;
        }

        .navbar-desktop.scrolled {
            padding: 6px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .navbar-desktop .navbar-brand {
            font-family: var(--heading-font);
            font-size: 1.6rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .navbar-desktop .navbar-brand img {
            width: 36px;
            height: 36px;
            border-radius: 10px;
        }

        .navbar-desktop .nav-link {
            color: #cbd5e1 !important;
            font-weight: 500;
            padding: 8px 14px !important;
            border-radius: 10px;
            transition: all 0.3s;
            font-size: 0.88rem;
            position: relative;
        }

        .navbar-desktop .nav-link:hover,
        .navbar-desktop .nav-link.active {
            color: #fff !important;
            background: rgba(102, 126, 234, 0.12);
        }

        .navbar-desktop .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px;
        }

        .user-avatar-nav {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #667eea;
        }

        /* ==========================================
           MOBILE BOTTOM NAVIGATION
           ========================================== */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 6px 0;
            padding-bottom: calc(6px + var(--safe-area-bottom));
            box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.3);
        }

        .mobile-bottom-nav .nav-list {
            display: flex;
            justify-content: space-around;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0 8px;
        }

        .mobile-bottom-nav .nav-item {
            text-align: center;
            flex: 1;
        }

        .mobile-bottom-nav .nav-link-bottom {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8 !important;
            text-decoration: none;
            font-size: 0.62rem;
            font-weight: 500;
            padding: 4px 6px !important;
            border-radius: 12px;
            transition: all 0.2s;
            background: transparent !important;
            position: relative;
        }

        .mobile-bottom-nav .nav-link-bottom i {
            font-size: 1.35rem;
            margin-bottom: 2px;
            transition: all 0.2s;
        }

        .mobile-bottom-nav .nav-link-bottom.active {
            color: #667eea !important;
        }

        .mobile-bottom-nav .nav-link-bottom.active i {
            color: #667eea;
            transform: scale(1.1);
        }

        .mobile-bottom-nav .nav-link-bottom.active::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 22px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px;
        }

        .mobile-bottom-nav .badge-count {
            position: absolute;
            top: 0;
            right: calc(50% - 14px);
            min-width: 15px;
            height: 15px;
            background: #ef4444;
            color: white;
            border-radius: 8px;
            font-size: 0.55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            font-weight: 700;
        }

        /* ==========================================
           MOBILE SIDEBAR (Hamburger Menu)
           ========================================== */
        .mobile-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .mobile-sidebar-overlay.show {
            opacity: 1;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100%;
            background: #0f172a;
            z-index: 1050;
            overflow-y: auto;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3);
        }

        .mobile-sidebar.show {
            left: 0;
        }

        .mobile-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-sidebar .sidebar-brand {
            font-family: var(--heading-font);
            font-size: 1.4rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mobile-sidebar .nav-link {
            padding: 13px 20px !important;
            color: #cbd5e1 !important;
            border-radius: 0;
            font-size: 0.88rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .mobile-sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .mobile-sidebar .nav-link:hover,
        .mobile-sidebar .nav-link.active {
            background: rgba(102, 126, 234, 0.12);
            color: #fff !important;
        }

        .mobile-sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.78rem;
            color: #64748b;
        }

        /* ==========================================
           BUTTONS
           ========================================== */
        .btn {
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 12px !important;
            transition: all 0.3s;
            font-size: 0.85rem;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.78rem;
        }

        /* ==========================================
           CARDS & CONTENT
           ========================================== */
        .card-custom {
            background: white;
            border-radius: 18px;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
            transition: all 0.3s;
        }

        .card-custom:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .card-custom .card-header-custom {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-custom .card-body-custom {
            padding: 20px;
        }

        /* ==========================================
           FOOTER
           ========================================== */
        .footer-premium {
            background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1e 100%);
            color: #cbd5e1;
            padding: 50px 0 0;
            position: relative;
        }

        .footer-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
        }

        .footer-link {
            color: #94a3b8;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .footer-link:hover {
            color: white;
            padding-left: 5px;
        }

        /* ==========================================
           ALERTS
           ========================================== */
        .alert-custom {
            border: none;
            border-radius: 14px;
            padding: 14px 20px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        /* ==========================================
           RESPONSIVE
           ========================================== */
        @media (max-width: 991.98px) {
            .mobile-bottom-nav { display: block; }
            body { padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-bottom)); }
            .footer-premium { padding-bottom: calc(80px + var(--safe-area-bottom)); }
            .navbar-desktop .collapse { display: none !important; }
            .navbar-desktop .navbar-toggler { display: flex !important; }
            main { padding-top: 40px; }
            .hide-mobile { display: none !important; }
        }

        @media (min-width: 992px) {
            .mobile-bottom-nav,
            .mobile-sidebar,
            .mobile-sidebar-overlay,
            .navbar-toggler-mobile { display: none !important; }
            body { padding-bottom: 0; }
            main { padding-top: 70px; }
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .mobile-bottom-nav { padding-bottom: calc(6px + env(safe-area-inset-bottom)); }
        }


        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 50%, rgba(255,255,255,0.05) 0%, transparent 50%);
            opacity: 0.5;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .page-content{
                margin-top: 25px;
            }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }

        .event-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .event-banner {
            height: 200px;
            object-fit: cover;
        }

        .event-banner-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 64px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary-gradient);
            margin: 20px auto;
            border-radius: 2px;
        }

        .announcement-item {
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 0 10px 10px 0;
            transition: all 0.3s;
            cursor: pointer;
        }

        .announcement-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .priority-urgent {
            border-left-color: #dc3545;
        }

        .priority-high {
            border-left-color: #fd7e14;
        }

        .footer {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
            padding: 60px 0 30px;
        }

        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }

        .btn-sm.rounded-circle {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pwa-install-prompt {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.3);
            z-index: 1000;
            align-items: center;
            gap: 15px;
        }

        .pwa-install-prompt.show {
            display: flex;
        }

        .logo-fallback {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .hero-image-fallback {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-section {
                padding: 60px 0 40px;
            }

            .stat-number {
                font-size: 2rem;
            }

            .page-content{
                margin-top: 75px;
            }
            .section-title{
                margin-top:30px;
            }
        }


        /* Employee Specific */
        /* Cards */
        .employee-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            margin-bottom: 16px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .employee-card:active {
            transform: scale(0.98);
        }

        .employee-card .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .employee-card .card-body {
            padding: 20px;
        }

        /* Stats */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-item {
            background: white;
            padding: 16px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .stat-item .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .stat-item .stat-label {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        /* Event Cards */
        .event-card-mobile {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            margin-bottom: 16px;
        }

        .event-card-mobile .event-banner {
            height: 160px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .event-card-mobile .event-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-card-mobile .event-info {
            padding: 16px;
        }

        /* Buttons */
        .btn-mobile {
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            transition: all 0.3s;
        }

        .btn-mobile:active {
            transform: scale(0.95);
            opacity: 0.9;
        }

        .btn-primary-mobile {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        /* Forms */
        .form-group-mobile {
            margin-bottom: 16px;
        }

        .form-label-mobile {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-mobile {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #fafafa;
        }

        .form-control-mobile:focus {
            border-color: #667eea;
            background: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Profile Avatar */
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
        }

        /* Page Transition */
        .page-content {
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pull to refresh indicator */
        .ptr-indicator {
            text-align: center;
            padding: 10px;
            color: #999;
            font-size: 13px;
            display: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- ==========================================
         DESKTOP NAVBAR
         ========================================== -->
    <nav class="navbar navbar-desktop navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-white.png') }}" alt="RSCB" onerror="this.style.display='none';">
                <span>RSCB</span>
            </a>

            <button class="navbar-toggler border-0" type="button" id="mobileSidebarToggle">
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
                    {{-- Logged in Employee --}}
                    <li class="nav-item d-flex align-items-center g-1">
    <a class="btn btn-outline-primary btn-sm px-2 py-1 text-nowrap" href="{{
        auth()->guard('employee')->user()->hasRole('super_admin') ? route('admin.dashboard') :
        (auth()->guard('employee')->user()->hasRole('regional_sports_secretary') ? route('admin.regional.dashboard') :
        (auth()->guard('employee')->user()->hasRole('airport_sports_secretary') ? route('admin.airport.dashboard') : route('employee.dashboard')))
    }}">
        <i class="bi bi-speedometer2 me-1"></i> Dashboard
    </a>

    <a class="btn btn-sm btn-outline-warning px-2 py-1 text-nowrap {{ request()->routeIs('employee.events*') ? 'active' : '' }}" href="{{ route('employee.events.available') }}">
        <i class="bi bi-calendar-check me-1"></i> My Events
    </a>

    <a class="btn btn-sm btn-outline-info px-2 py-1 text-nowrap {{ request()->routeIs('employee.registrations*') ? 'active' : '' }}" href="{{ route('employee.registrations.index') }}">
        <i class="bi bi-clipboard-check me-1"></i> Registrations
    </a>
</li>


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            @if(auth()->guard('employee')->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->guard('employee')->user()->profile_photo) }}"
                                     class="user-avatar-nav me-2">
                            @else
                                <div class="user-avatar-nav bg-primary d-flex align-items-center justify-content-center text-white fw-bold me-2" style="font-size:14px;">
                                    {{ strtoupper(substr(auth()->guard('employee')->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="d-none d-md-inline">{{ auth()->guard('employee')->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
                            <li class="px-3 py-2">
                                <small class="text-muted">{{ auth()->guard('employee')->user()->employee_id }}</small>
                                <strong class="d-block">{{ auth()->guard('employee')->user()->email }}</strong>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('employee.profile.show') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.notifications.index') }}"><i class="bi bi-bell me-2"></i> Notifications</a></li>
                            <li><a class="dropdown-item" href="{{ route('employee.password.change') }}"><i class="bi bi-key me-2"></i> Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('employee.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    {{-- Guest --}}
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('employee.login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Employee Login
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         MOBILE SIDEBAR
         ========================================== -->
    <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="mobile-sidebar-header">
            <span class="sidebar-brand">RSCB</span>
            <button class="btn-close btn-close-white" id="mobileSidebarClose"></button>
        </div>

        <div class="py-2">
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
                <i class="bi bi-person"></i>My Profile
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
            @else
            <hr class="my-2 border-secondary opacity-25">
            <div class="px-3 d-grid">
                <a href="{{ route('employee.login') }}" class="btn btn-primary btn-sm">Employee Login</a>
            </div>
            @endauth

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

    <!-- ==========================================
         MOBILE BOTTOM NAVIGATION
         ========================================== -->
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
                    <span>All Events</span>
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
                        @php $pendingCount = \App\Models\EventRegistration::where('employee_id', auth()->guard('employee')->id())->where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)<span class="badge-count">{{ $pendingCount }}</span>@endif
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

    <!-- ==========================================
         MAIN CONTENT
         ========================================== -->
    <main>
        <div class="container-fluid p-0 pt-0">
            {{-- Alerts --}}
            @if(session('success'))
            <div class="alert alert-success alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <div>{{ session('warning') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-custom animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-custom animate__animated animate__fadeInDown" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- ==========================================
         FOOTER
         ========================================== -->
    <footer class="footer-premium mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-3 d-flex align-items-center">
                        <i class="bi bi-trophy-fill me-2 fs-4" style="color: #667eea;"></i>RSCB
                    </h5>
                    <p class="text-white-50 mb-4">Promoting sports and fitness among airport employees across all regions of India.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white mb-3 fw-bold">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('website.events') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Events</a></li>
                        <li class="mb-2"><a href="{{ route('website.announcements') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Announcements</a></li>
                        <li class="mb-2"><a href="{{ route('website.gallery') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Gallery</a></li>
                        <li class="mb-2"><a href="{{ route('website.winners') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Winners</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white mb-3 fw-bold">Resources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('website.downloads') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Downloads</a></li>
                        <li class="mb-2"><a href="{{ route('website.faq') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>FAQ</a></li>
                        <li class="mb-2"><a href="{{ route('website.help') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Help Center</a></li>
                        <li class="mb-2"><a href="{{ route('website.about') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>About Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white mb-3 fw-bold">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('website.privacy') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('website.terms') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Terms & Conditions</a></li>
                        <li class="mb-2"><a href="{{ route('website.disclaimer') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Disclaimer</a></li>
                        <li class="mb-2"><a href="{{ route('website.accessibility') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i>Accessibility</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white mb-3 fw-bold">Contact</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-white-50 small"><i class="bi bi-geo-alt me-2"></i>Kolkata, West Bengal, India</li>
                        <li class="mb-2"><a href="mailto:info@rscb.aai.aero" class="footer-link"><i class="bi bi-envelope me-2"></i>info@rscb.aai.aero</a></li>
                        <li class="mb-2 text-white-50 small"><i class="bi bi-telephone me-2"></i>+91-XXXXXXXXXX</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-20">
            <div class="row align-items-center pb-4">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} <strong class="text-white">RSCB</strong>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <small class="text-white-50">Version 1.0.0 | Made with <i class="bi bi-heart-fill text-danger"></i> for Sports</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-primary btn-sm rounded-circle position-fixed shadow-lg"
            style="display: none; width: 44px; height: 44px; bottom: 80px; right: 20px; z-index: 1000;">
        <i class="bi bi-arrow-up"></i>
    </button>

    @stack('scripts')

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            var nav = document.getElementById('mainNav');
            var backToTop = document.getElementById('backToTop');
            if (window.scrollY > 50) {
                if (nav) nav.classList.add('scrolled');
                if (backToTop) backToTop.style.display = 'flex';
            } else {
                if (nav) nav.classList.remove('scrolled');
                if (backToTop) backToTop.style.display = 'none';
            }
        });

        // Back to top
        var backToTopBtn = document.getElementById('backToTop');
        if (backToTopBtn) {
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Mobile Sidebar
        var sidebar = document.getElementById('mobileSidebar');
        var overlay = document.getElementById('mobileSidebarOverlay');

        function openSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        var sidebarToggle = document.getElementById('mobileSidebarToggle');
        if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);

        var mobileMenuBtn = document.getElementById('mobileMenuBtn');
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function(e) { e.preventDefault(); openSidebar(); });
        }

        var sidebarClose = document.getElementById('mobileSidebarClose');
        if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) closeSidebar();
        });

        // Hide bottom nav on scroll down
        var lastScrollTop = 0;
        var bottomNav = document.getElementById('mobileBottomNav');
        if (bottomNav && window.innerWidth < 992) {
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    bottomNav.style.transform = 'translateY(100%)';
                    bottomNav.style.transition = 'transform 0.3s ease';
                } else {
                    bottomNav.style.transform = 'translateY(0)';
                }
                lastScrollTop = scrollTop;
            });
        }
    </script>
</body>
</html>
