{{-- resources/views/layouts/partials/styles/animations.blade.php --}}
<style>
    /* ==========================================
       KEYFRAME ANIMATIONS
       ========================================== */
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

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* ==========================================
       ANIMATION UTILITY CLASSES
       ========================================== */
    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }

    .animate__fadeInUp {
        animation-name: fadeInUp;
    }

    .animate__fadeInDown {
        animation-name: fadeInDown;
    }

    .animate__fadeIn {
        animation-name: fadeIn;
    }

    .animate__pulse {
        animation-name: pulse;
        animation-duration: 2s;
        animation-iteration-count: infinite;
    }
</style>
