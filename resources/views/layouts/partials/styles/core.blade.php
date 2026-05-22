{{-- resources/views/layouts/partials/styles/core.blade.php --}}
<style>
    :root {
        /* Primary Colors */
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --primary-light: #818cf8;
        --secondary: #764ba2;
        --secondary-light: #a78bfa;

        /* Neutral Colors */
        --dark: #0f172a;
        --dark-secondary: #1e293b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;

        /* Status Colors */
        --success: #10b981;
        --success-light: #d1fae5;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --info: #3b82f6;
        --info-light: #dbeafe;

        /* Typography */
        --body-font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        --heading-font: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

        /* Layout Dimensions */
        --topbar-height: 60px;
        --bottom-nav-height: 68px;
        --sidebar-width: 280px;
        --safe-area-bottom: env(safe-area-inset-bottom, 0px);

        /* Borders & Shadows */
        --border-radius-sm: 8px;
        --border-radius: 12px;
        --border-radius-lg: 16px;
        --border-radius-xl: 20px;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
        --shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.12);
        --shadow-xl: 0 15px 50px rgba(0, 0, 0, 0.15);

        /* Transitions */
        --transition-fast: 0.2s ease;
        --transition: 0.3s ease;
        --transition-slow: 0.5s ease;
    }

    /* ==========================================
       CSS RESET & BASE STYLES
       ========================================== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
        -webkit-tap-highlight-color: transparent;
    }

    body {
        font-family: var(--body-font);
        background: var(--gray-100);
        color: var(--gray-800);
        line-height: 1.7;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--heading-font);
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.3;
    }

    h1 { font-size: 2.5rem; }
    h2 { font-size: 2rem; }
    h3 { font-size: 1.5rem; }
    h4 { font-size: 1.25rem; }
    h5 { font-size: 1.1rem; }
    h6 { font-size: 1rem; }

    a {
        color: var(--primary);
        text-decoration: none;
        transition: var(--transition-fast);
    }

    a:hover {
        color: var(--primary-dark);
    }

    img {
        max-width: 100%;
        height: auto;
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    ::selection {
        background: var(--primary);
        color: white;
    }

    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: var(--gray-100);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--gray-400);
    }
</style>
