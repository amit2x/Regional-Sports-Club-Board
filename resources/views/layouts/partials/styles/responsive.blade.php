{{-- resources/views/layouts/partials/styles/responsive.blade.php --}}
<style>
    /* ==========================================
       MOBILE (< 992px)
       ========================================== */
    @media (max-width: 991.98px) {
        .mobile-bottom-nav {
            display: block;
        }

        body {
            padding-bottom: calc(var(--bottom-nav-height) + var(--safe-area-bottom));
        }

        .footer-premium {
            padding-bottom: calc(80px + var(--safe-area-bottom));
        }

        .navbar-desktop .collapse {
            display: none !important;
        }

        .navbar-desktop .navbar-toggler {
            display: flex !important;
        }

        main {
            padding-top: 40px;
        }

        .hide-mobile {
            display: none !important;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-section {
            padding: 60px 0 40px;
        }

        .stat-number {
            font-size: 2rem;
        }

        .page-content {
            margin-top: 75px;
        }

        .section-title {
            margin-top: 30px;
        }
    }

    /* ==========================================
       TABLET (768px - 991px)
       ========================================== */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .stat-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* ==========================================
       DESKTOP (≥ 992px)
       ========================================== */
    @media (min-width: 992px) {
        .mobile-bottom-nav,
        .mobile-sidebar,
        .mobile-sidebar-overlay,
        .navbar-toggler-mobile {
            display: none !important;
        }

        body {
            padding-bottom: 0;
        }

        main {
            padding-top: 70px;
        }
    }

    /* ==========================================
       LARGE DESKTOP (≥ 1200px)
       ========================================== */
    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }

    /* ==========================================
       EXTRA LARGE DESKTOP (≥ 1400px)
       ========================================== */
    @media (min-width: 1400px) {
        .container {
            max-width: 1320px;
        }
    }

    /* ==========================================
       PRINT STYLES
       ========================================== */
    @media print {
        .mobile-bottom-nav,
        .navbar-desktop,
        .mobile-sidebar,
        .footer-premium,
        #backToTop {
            display: none !important;
        }

        body {
            padding: 0;
            background: white;
        }

        main {
            padding-top: 0;
        }
    }
</style>
