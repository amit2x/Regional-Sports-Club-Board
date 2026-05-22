{{-- resources/views/layouts/partials/styles/components.blade.php --}}
<style>
    /* ==========================================
       BUTTONS
       ========================================== */
    .btn {
        font-weight: 600;
        padding: 10px 20px;
        border-radius: var(--border-radius) !important;
        transition: var(--transition);
        font-size: 0.85rem;
        position: relative;
        overflow: hidden;
    }

    .btn:active {
        transform: scale(0.96);
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
        background: transparent;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary.active {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-outline-light {
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        background: transparent;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .btn-sm {
        padding: 6px 14px;
        font-size: 0.78rem;
        border-radius: var(--border-radius-sm) !important;
    }

    .btn-lg {
        padding: 14px 28px;
        font-size: 1rem;
        border-radius: var(--border-radius-lg) !important;
    }

    .btn-sm.rounded-circle {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* ==========================================
       CARDS
       ========================================== */
    .card-custom {
        background: white;
        border-radius: var(--border-radius-xl);
        border: none;
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
    }

    .card-custom:hover {
        box-shadow: var(--shadow-md);
    }

    .card-custom .card-header-custom {
        padding: 16px 20px;
        border-bottom: 1px solid var(--gray-100);
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
    }

    .card-custom .card-body-custom {
        padding: 20px;
    }

    /* ==========================================
       STATS CARDS
       ========================================== */
    .stat-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 25px;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--gray-500);
        margin-top: 4px;
    }

    /* ==========================================
       EVENT CARDS
       ========================================== */
    .event-card {
        border: none;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow);
        background: white;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .event-banner {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .event-banner-placeholder {
        height: 200px;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 64px;
    }

    .event-card-mobile {
        background: white;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow);
        margin-bottom: 16px;
    }

    .event-card-mobile:active {
        transform: scale(0.98);
    }

    .event-card-mobile .event-banner {
        height: 160px;
        background: var(--primary-gradient);
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

    /* ==========================================
       BADGES
       ========================================== */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.pending {
        background: var(--warning-light);
        color: #92400e;
    }

    .status-badge.approved {
        background: var(--success-light);
        color: #065f46;
    }

    .status-badge.rejected {
        background: var(--danger-light);
        color: #991b1b;
    }

    .badge-count {
        position: absolute;
        top: -2px;
        right: calc(50% - 14px);
        min-width: 16px;
        height: 16px;
        background: var(--danger);
        color: white;
        border-radius: 8px;
        font-size: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        font-weight: 700;
        line-height: 1;
    }

    /* ==========================================
       ANNOUNCEMENTS
       ========================================== */
    .announcement-item {
        border-left: 4px solid var(--primary);
        padding: 15px 20px;
        margin-bottom: 15px;
        background: var(--gray-50);
        border-radius: 0 var(--border-radius) var(--border-radius) 0;
        transition: var(--transition);
        cursor: pointer;
    }

    .announcement-item:hover {
        background: var(--gray-200);
        transform: translateX(5px);
    }

    .announcement-item.priority-urgent {
        border-left-color: var(--danger);
    }

    .announcement-item.priority-high {
        border-left-color: var(--warning);
    }

    /* ==========================================
       ALERTS
       ========================================== */
    .alert-custom {
        border: none;
        border-radius: var(--border-radius);
        padding: 14px 20px;
        font-weight: 500;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
    }

    /* ==========================================
       AVATARS
       ========================================== */
    .user-avatar-nav {
        width: 34px;
        height: 34px;
        border-radius: var(--border-radius-sm);
        object-fit: cover;
        border: 2px solid var(--primary);
        flex-shrink: 0;
    }

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
        flex-shrink: 0;
    }

    /* ==========================================
       FORMS
       ========================================== */
    .form-label-mobile {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 6px;
        display: block;
    }

    .form-control-mobile {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius);
        font-size: 0.9375rem;
        transition: var(--transition);
        background: var(--gray-50);
    }

    .form-control-mobile:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group-mobile {
        margin-bottom: 16px;
    }

    /* ==========================================
       SECTION TITLES
       ========================================== */
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
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
        margin: 20px auto 0;
        border-radius: 2px;
    }

    /* ==========================================
       MISC
       ========================================== */
    .logo-fallback {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: var(--border-radius-sm);
        font-weight: 700;
        font-size: 1.5rem;
    }

    .hero-image-fallback {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        border-radius: var(--border-radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ptr-indicator {
        text-align: center;
        padding: 10px;
        color: var(--gray-400);
        font-size: 0.8125rem;
        display: none;
    }
</style>
