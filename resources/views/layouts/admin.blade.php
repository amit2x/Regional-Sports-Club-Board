{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#667eea">
    <title>@yield('title', 'Dashboard') | RSCB Management</title>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 0px;
            --topbar-height: 70px;
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --light: #f7fafc;
        }

        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background: #f0f2f5;
            color: #333;
            overflow-x: hidden;
        }

        /* Admin Wrapper */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Premium Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 5px 0 25px rgba(0,0,0,0.15);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.02);
        }

        .sidebar-header .brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .sidebar-header .brand-logo img {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            transition: transform 0.3s;
        }

        .sidebar-header .brand-logo:hover img {
            transform: scale(1.05);
        }

        .brand-text {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            padding: 20px 15px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 18px;
            margin-bottom: 5px;
            border-radius: 12px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            white-space: nowrap;
        }

        .sidebar .nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            transition: transform 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(102,126,234,0.3), rgba(118,75,162,0.3));
            box-shadow: 0 5px 15px rgba(102,126,234,0.2);
            border-left: 3px solid #667eea;
        }

        .sidebar .nav-link .badge {
            margin-left: auto;
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .sidebar .has-submenu .nav-link::after {
            content: '\F282';
            font-family: 'bootstrap-icons';
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s;
        }

        .sidebar .has-submenu .nav-link[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .sidebar .sub-menu {
            padding-left: 25px;
            margin: 5px 0;
        }

        .sidebar .sub-menu .nav-link {
            padding: 10px 18px;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .sidebar .sub-menu .nav-link i {
            font-size: 14px;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 15px 0;
            padding: 0 15px;
        }

        .sidebar-divider span {
            color: rgba(255,255,255,0.4);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f0f2f5;
        }

        /* Premium Top Navbar */
        .top-navbar {
            background: #fff;
            height: var(--topbar-height);
            padding: 0 25px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(10px);
        }

        .sidebar-toggler {
            background: none;
            border: none;
            color: #666;
            font-size: 24px;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .sidebar-toggler:hover {
            background: #f0f2f5;
            color: #667eea;
        }

        .notification-btn {
            position: relative;
            color: #666;
            font-size: 22px;
            padding: 8px 12px;
            border-radius: 12px;
            transition: all 0.3s;
            background: none;
            border: none;
        }

        .notification-btn:hover {
            background: #f0f2f5;
            color: #667eea;
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background: #f56565;
            border-radius: 50%;
            border: 2px solid #fff;
            animation: pulse 2s infinite;
        }

        .notification-menu {
            width: 380px;
            max-height: 480px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            border-radius: 16px;
            overflow: hidden;
        }

        .notification-header {
            padding: 18px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 700;
            color: #333;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }

        .notification-item:hover {
            background: #f7f8fc;
        }

        .notification-item.unread {
            background: #eef0ff;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .notification-content p {
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .notification-footer {
            padding: 15px 20px;
            text-align: center;
            background: #fafafa;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 14px;
            transition: all 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            color: #333;
        }

        .user-profile-btn:hover {
            background: #f0f2f5;
        }

        .user-profile-btn img, .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #667eea;
            transition: transform 0.3s;
        }

        .user-profile-btn:hover img {
            transform: scale(1.05);
        }

        .user-info {
            text-align: left;
        }

        .user-info .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        .user-info .user-role {
            font-size: 12px;
            color: #999;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding: 25px;
            min-height: calc(100vh - var(--topbar-height) - 60px);
        }

        /* Page Header */
        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
        }

        .breadcrumb-item {
            font-size: 13px;
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #999;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 16px 16px 0 0 !important;
            padding: 18px 24px;
        }

        .card-body {
            padding: 24px;
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
            letter-spacing: 0.3px;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8, #6a3f8a);
        }

        /* Footer */
        .admin-footer {
            background: #fff;
            padding: 16px 25px;
            border-top: 1px solid #f0f0f0;
            color: #999;
            font-size: 13px;
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .admin-footer {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <div class="main-content" id="mainContent">
            {{-- Top Navigation --}}
            @include('admin.partials.navbar')

            {{-- Page Content --}}
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('admin.partials.footer')

    @stack('scripts')

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                const sidebar = document.querySelector('.sidebar');
                const toggle = document.getElementById('sidebarToggle');
                if (!sidebar.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Mark notification as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.dataset.id;
                if (notificationId) {
                    fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                }
                window.location.href = this.href;
            });
        });

        // Mark all as read
        const markAllReadBtn = document.querySelector('.mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });
                    document.querySelector('.notification-badge').style.display = 'none';
                });
            });
        }
    </script>
</body>
</html>
