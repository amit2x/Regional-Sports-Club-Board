{{-- resources/views/layouts/partials/scripts/core.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // NAVBAR SCROLL EFFECT
        // ==========================================
        const mainNav = document.getElementById('mainNav');
        const backToTopBtn = document.getElementById('backToTop');
        let lastScrollTop = 0;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Navbar effect
            if (mainNav) {
                if (scrollTop > 50) {
                    mainNav.classList.add('scrolled');
                } else {
                    mainNav.classList.remove('scrolled');
                }
            }

            // Back to top button
            if (backToTopBtn) {
                backToTopBtn.style.display = scrollTop > 300 ? 'flex' : 'none';
            }

            lastScrollTop = scrollTop;
        });

        // ==========================================
        // BACK TO TOP
        // ==========================================
        if (backToTopBtn) {
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // ==========================================
        // MOBILE SIDEBAR
        // ==========================================
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('mobileSidebarOverlay');
        const sidebarToggle = document.getElementById('mobileSidebarToggle');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarClose = document.getElementById('mobileSidebarClose');

        function openSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
                document.body.style.overflow = '';
            }
        }

        if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openSidebar();
            });
        }
        if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Close sidebar on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                closeSidebar();
            }
        });

        // ==========================================
        // MOBILE BOTTOM NAV - HIDE ON SCROLL
        // ==========================================
        const bottomNav = document.getElementById('mobileBottomNav');
        if (bottomNav && window.innerWidth < 992) {
            let navLastScroll = 0;

            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > navLastScroll && scrollTop > 100) {
                    bottomNav.style.transform = 'translateY(100%)';
                    bottomNav.style.transition = 'transform 0.3s ease';
                } else {
                    bottomNav.style.transform = 'translateY(0)';
                }

                navLastScroll = scrollTop;
            });
        }

        // ==========================================
        // AUTO-DISMISS ALERTS
        // ==========================================
        document.querySelectorAll('.alert-custom').forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
