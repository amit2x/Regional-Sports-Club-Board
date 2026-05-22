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

    {{-- Core Styles --}}
    @include('layouts.partials.styles.core')

    {{-- Component Styles --}}
    @include('layouts.partials.styles.components')

    {{-- Layout Styles --}}
    @include('layouts.partials.styles.layout')

    {{-- Employee Specific Styles --}}
    @include('layouts.partials.styles.employee')

    {{-- Animation Styles --}}
    @include('layouts.partials.styles.animations')

    {{-- Responsive Styles --}}
    @include('layouts.partials.styles.responsive')

    @stack('styles')
</head>
<body>
    {{-- Desktop Navigation --}}
    @include('layouts.partials.navigation.desktop')

    {{-- Mobile Sidebar --}}
    @include('layouts.partials.navigation.mobile-sidebar')

    {{-- Mobile Bottom Navigation --}}
    @include('layouts.partials.navigation.mobile-bottom')

    {{-- Main Content --}}
    <main>
        <div class="container-fluid p-0 pt-0">
            {{-- Alert Messages --}}
            @include('layouts.partials.alerts')

            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.partials.public-footer')

    {{-- Back to Top Button --}}
    @include('layouts.partials.back-to-top')

    {{-- Scripts --}}
    @stack('scripts')

    {{-- Core JavaScript --}}
    @include('layouts.partials.scripts.core')
</body>
</html>
