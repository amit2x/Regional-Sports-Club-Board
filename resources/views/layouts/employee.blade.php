{{-- resources/views/employee/layouts/employee.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'RSCB') - Employee Portal</title>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --bottom-nav-height: 65px;
            --topbar-height: 56px;
            --safe-area-bottom: env(safe-area-inset-bottom, 0px);
        }

        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background: #f5f6fa;
            padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-bottom) + 20px);
            padding-top: var(--topbar-height);
            -webkit-tap-highlight-color: transparent;
        }

        /* Top Bar */
        .employee-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: linear-gradient(135deg, #667eea, #764ba2);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 16px;
            box-shadow: 0 2px 20px rgba(102,126,234,0.3);
        }

        .employee-topbar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
        }

        .employee-topbar .brand img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }

        .employee-topbar .topbar-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-btn {
            position: relative;
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .topbar-btn:hover {
            background: rgba(255,255,255,0.25);
        }

        .topbar-btn .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: calc(var(--bottom-nav-height) + var(--safe-area-bottom));
            background: white;
            display: flex;
            align-items: center;
            padding-bottom: var(--safe-area-bottom);
            z-index: 1000;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.08);
        }

        .bottom-nav .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #999;
            padding: 8px 4px;
            transition: all 0.3s;
            position: relative;
            gap: 4px;
        }

        .bottom-nav .nav-item i {
            font-size: 22px;
            transition: all 0.3s;
        }

        .bottom-nav .nav-item span {
            font-size: 10px;
            font-weight: 500;
        }

        .bottom-nav .nav-item.active {
            color: #667eea;
        }

        .bottom-nav .nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: #667eea;
            border-radius: 0 0 3px 3px;
        }

        .bottom-nav .nav-item .badge {
            position: absolute;
            top: 2px;
            right: calc(50% - 20px);
            font-size: 9px;
            padding: 3px 6px;
        }

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
</head>
<body>
    {{-- Top Bar --}}
    <div class="employee-topbar">
        <a href="{{ route('employee.dashboard') }}" class="brand">
            <img src="{{ asset('images/logo-white.png') }}" alt="RSCB" onerror="this.style.display='none';">
            <span>RSCB</span>
        </a>
        <div class="topbar-actions">
            <button class="topbar-btn" onclick="window.location.href='{{ route('notifications.index') }}'" title="Notifications">
                <i class="bi bi-bell-fill"></i>
                @php
                    $unreadCount = auth()->guard('employee')->check() ?
                        auth()->guard('employee')->user()->unreadNotifications->count() : 0;
                @endphp
                @if($unreadCount > 0)
                    <span class="badge bg-danger">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>
            <button class="topbar-btn" onclick="window.location.href='{{ route('profile.show') }}'" title="Profile">
                @if(auth()->guard('employee')->check() && auth()->guard('employee')->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->guard('employee')->user()->profile_photo) }}"
                         style="width: 38px; height: 38px; border-radius: 12px; object-fit: cover;">
                @else
                    <i class="bi bi-person-fill"></i>
                @endif
            </button>
        </div>
    </div>

    {{-- Main Content --}}
    <main>
        <div class="container py-3 page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Bottom Navigation --}}
    <div class="bottom-nav">
        <a href="{{ route('employee.dashboard') }}" class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('employee.events.available') }}" class="nav-item {{ request()->routeIs('employee.events.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event-fill"></i>
            <span>Events</span>
        </a>
        <a href="{{ route('employee.registrations.index') }}" class="nav-item {{ request()->routeIs('employee.registrations.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check-fill"></i>
            <span>Registrations</span>
        </a>
        <a href="{{ route('employee.profile.show') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            <span>Profile</span>
        </a>
        <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>

    <form id="logoutForm" method="POST" action="{{ route('employee.logout') }}" class="d-none">
        @csrf
    </form>

    @stack('scripts')

    <script>
        // Double tap to scroll to top
        let lastTap = 0;
        document.addEventListener('click', function(e) {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            if (tapLength < 300 && tapLength > 0) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            lastTap = currentTime;
        });
    </script>
</body>
</html>
