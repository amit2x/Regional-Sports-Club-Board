{{-- resources/views/layouts/partials/styles/employee.blade.php --}}
<style>
    /* ==========================================
       EMPLOYEE CARDS
       ========================================== */
    .employee-card {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow);
        margin-bottom: 16px;
        overflow: hidden;
        transition: var(--transition);
    }

    .employee-card:active {
        transform: scale(0.98);
    }

    .employee-card .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--gray-100);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
    }

    .employee-card .card-body {
        padding: 20px;
    }

    /* ==========================================
       EMPLOYEE STATS GRID
       ========================================== */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .stat-item {
        background: white;
        padding: 16px;
        border-radius: var(--border-radius);
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .stat-item .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-item .stat-label {
        font-size: 12px;
        color: var(--gray-400);
        margin-top: 4px;
    }

    /* ==========================================
       EMPLOYEE BUTTONS
       ========================================== */
    .btn-mobile {
        border-radius: var(--border-radius);
        padding: 12px 20px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        transition: var(--transition);
    }

    .btn-mobile:active {
        transform: scale(0.95);
        opacity: 0.9;
    }

    .btn-primary-mobile {
        background: var(--primary-gradient);
        color: white;
    }

    /* ==========================================
       EMPLOYEE PAGE CONTENT
       ========================================== */
    .page-content {
        margin-top: 25px;
    }

    /* ==========================================
       HERO SECTION
       ========================================== */
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
        background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
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
</style>
