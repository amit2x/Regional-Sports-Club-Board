{{-- resources/views/layouts/partials/styles/layout.blade.php --}}
<style>
    /* ==========================================
       DESKTOP TOP NAVBAR
       ========================================== */
    .navbar-desktop {
        background: rgba(15, 23, 42, 0.97) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 10px 0;
        transition: var(--transition);
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
        color: var(--gray-300) !important;
        font-weight: 500;
        padding: 8px 14px !important;
        border-radius: 10px;
        transition: var(--transition);
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
        -webkit-backdrop-filter: blur(20px);
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
        color: var(--gray-400) !important;
        text-decoration: none;
        font-size: 0.62rem;
        font-weight: 500;
        padding: 4px 6px !important;
        border-radius: var(--border-radius);
        transition: var(--transition-fast);
        background: transparent !important;
        position: relative;
    }

    .mobile-bottom-nav .nav-link-bottom i {
        font-size: 1.35rem;
        margin-bottom: 2px;
        transition: var(--transition-fast);
    }

    .mobile-bottom-nav .nav-link-bottom.active {
        color: var(--primary) !important;
    }

    .mobile-bottom-nav .nav-link-bottom.active i {
        color: var(--primary);
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

    /* ==========================================
       MOBILE SIDEBAR
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
        transition: opacity var(--transition);
    }

    .mobile-sidebar-overlay.show {
        opacity: 1;
    }

    .mobile-sidebar {
        position: fixed;
        top: 0;
        left: calc(-1 * var(--sidebar-width));
        width: var(--sidebar-width);
        height: 100%;
        background: var(--gray-900);
        z-index: 1050;
        overflow-y: auto;
        transition: left var(--transition) cubic-bezier(0.4, 0, 0.2, 1);
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
        color: var(--gray-300) !important;
        border-radius: 0;
        font-size: 0.88rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: var(--transition-fast);
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
        color: var(--gray-500);
    }

    /* ==========================================
       FOOTER
       ========================================== */
    .footer-premium {
        background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1e 100%);
        color: var(--gray-300);
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
        color: var(--gray-400);
        transition: var(--transition);
        text-decoration: none;
        font-size: 0.85rem;
    }

    .footer-link:hover {
        color: white;
        padding-left: 5px;
    }

    .footer-link i {
        font-size: 0.7rem;
    }

    /* ==========================================
       PAGE CONTENT
       ========================================== */
    .page-content {
        animation: fadeInUp 0.3s ease;
    }

    @supports (padding-bottom: env(safe-area-inset-bottom)) {
        .mobile-bottom-nav {
            padding-bottom: calc(6px + env(safe-area-inset-bottom));
        }
    }
</style>
