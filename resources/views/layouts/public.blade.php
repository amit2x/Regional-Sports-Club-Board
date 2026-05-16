{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Regional Sports Control Board - Centralized sports management portal">
    <meta name="theme-color" content="#667eea">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ route('manifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <title>@yield('title', 'Regional Sports Control Board')</title>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
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
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-white.png') }}"
                     alt="RSCB"
                     height="40"
                     class="me-2"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <span class="logo-fallback" style="display: none;">RSCB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.events*') ? 'active' : '' }}" href="{{ route('website.events') }}">
                            <i class="bi bi-calendar-event me-1"></i>Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.announcements*') ? 'active' : '' }}" href="{{ route('website.announcements') }}">
                            <i class="bi bi-megaphone me-1"></i>Announcements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.gallery*') ? 'active' : '' }}" href="{{ route('website.gallery') }}">
                            <i class="bi bi-images me-1"></i>Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.winners*') ? 'active' : '' }}" href="{{ route('website.winners') }}">
                            <i class="bi bi-trophy me-1"></i>Winners
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.contact*') ? 'active' : '' }}" href="{{ route('website.contact') }}">
                            <i class="bi bi-envelope me-1"></i>Contact
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('employee.login') }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <h5 class="mb-3">Regional Sports Control Board</h5>
                    <p class="text-white-50">
                        Promoting sports and fitness among airport employees across all regions.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>

                </div>
                <div class="col-lg-2 col-md-3">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('website.events') }}"><i class="bi bi-chevron-right me-1"></i>Events</a></li>
                        <li class="mb-2"><a href="{{ route('website.announcements') }}"><i class="bi bi-chevron-right me-1"></i>Announcements</a></li>
                        <li class="mb-2"><a href="{{ route('website.gallery') }}"><i class="bi bi-chevron-right me-1"></i>Gallery</a></li>
                        <li class="mb-2"><a href="{{ route('website.winners') }}"><i class="bi bi-chevron-right me-1"></i>Winners</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3">
                    <h6 class="mb-3">Resources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('website.downloads') }}"><i class="bi bi-chevron-right me-1"></i>Downloads</a></li>
                        <li class="mb-2"><a href="{{ route('website.faq') }}"><i class="bi bi-chevron-right me-1"></i>FAQ</a></li>
                        <li class="mb-2"><a href="{{ route('website.help') }}"><i class="bi bi-chevron-right me-1"></i>Help Center</a></li>
                        <li class="mb-2"><a href="{{ route('website.about') }}"><i class="bi bi-chevron-right me-1"></i>About Us</a></li>
                        <li class="mb-2"><a href="{{ route('website.feedback') }}"><i class="bi bi-chevron-right me-1"></i>Feedback</a></li>
                        <li class="mb-2"><a href="{{ route('website.sitemap') }}"><i class="bi bi-chevron-right me-1"></i>Site Map</a></li>
                    </ul>
                </div>

                {{-- Add Legal column --}}
                <div class="col-lg-2 col-md-3">
                    <h6 class="mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('website.terms') }}"><i class="bi bi-chevron-right me-1"></i>Terms & Conditions</a></li>
                        <li class="mb-2"><a href="{{ route('website.privacy') }}"><i class="bi bi-chevron-right me-1"></i>Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('website.disclaimer') }}"><i class="bi bi-chevron-right me-1"></i>Disclaimer</a></li>
                        <li class="mb-2"><a href="{{ route('website.copyright') }}"><i class="bi bi-chevron-right me-1"></i>Copyright</a></li>
                        <li class="mb-2"><a href="{{ route('website.accessibility') }}"><i class="bi bi-chevron-right me-1"></i>Accessibility</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-5">
                    <h6 class="mb-3">Contact Us</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Kolkata,West Bengal, India</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> <a href="mailto:info@rscb.aai.aero">info@rscb.aai.aero </a></li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>+91-XXXXXXXX</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-white-50">&copy; {{ date('Y') }} Regional Sports Control Board. All rights reserved.</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-white-50">Version 1.0.0</small>
                </div>
            </div>
        </div>
    </footer>

    {{-- PWA Install Prompt --}}
    <div id="pwaInstallPrompt" class="pwa-install-prompt">
        <i class="bi bi-download fs-4"></i>
        <div>
            <strong>Install RSCB App</strong><br>
            <small>Add to home screen for quick access</small>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-4" onclick="installPWA()">
            Install
        </button>
        <button class="btn btn-link text-muted p-0" onclick="dismissPWA()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    @stack('scripts')

    <script>
        // Counter animation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.stat-number').forEach(function(element) {
                const target = parseInt(element.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const timer = setInterval(function() {
                    current += step;
                    if (current >= target) {
                        element.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current).toLocaleString();
                    }
                }, 16);
            });

            // Handle image errors
            document.querySelectorAll('img').forEach(function(img) {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                });
            });
        });

        // PWA Installation
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            setTimeout(function() {
                const prompt = document.getElementById('pwaInstallPrompt');
                if (prompt && !getCookie('pwa_dismissed')) {
                    prompt.classList.add('show');
                }
            }, 3000);
        });

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                    document.getElementById('pwaInstallPrompt').classList.remove('show');
                });
            }
        }

        function dismissPWA() {
            document.getElementById('pwaInstallPrompt').classList.remove('show');
            setCookie('pwa_dismissed', 'true', 7);
        }

        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
        }

        function getCookie(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        // Check if previously dismissed
        if (getCookie('pwa_dismissed') === 'true') {
            const prompt = document.getElementById('pwaInstallPrompt');
            if (prompt) prompt.style.display = 'none';
        }

        // Register service worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>
</html>
