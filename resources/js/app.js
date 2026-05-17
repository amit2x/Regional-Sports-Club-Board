// resources/js/app.js

import './bootstrap';

// Import jQuery globally
import $ from 'jquery';
window.$ = window.jQuery = $;

// Import SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Import Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import DataTables core and extensions
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-responsive-bs5';

// Import Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// CSRF Token setup for AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    });
}

// Global AJAX error handler
$(document).ajaxError(function(event, xhr, settings, error) {
    console.error('AJAX Error:', {
        status: xhr.status,
        statusText: xhr.statusText,
        url: settings.url,
        error: error
    });

    if (xhr.status === 401) {
        window.location.href = '/employee/login';
    } else if (xhr.status === 403) {
        Swal.fire({
            icon: 'error',
            title: 'Access Denied',
            text: 'You do not have permission to perform this action.',
            confirmButtonColor: '#3085d6'
        });
    } else if (xhr.status === 419) {
        Swal.fire({
            icon: 'warning',
            title: 'Session Expired',
            text: 'Your session has expired. Please refresh the page.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            location.reload();
        });
    } else if (xhr.status === 422) {
        if (xhr.responseJSON && xhr.responseJSON.errors) {
            displayValidationErrors(xhr.responseJSON.errors);
        }
    } else if (xhr.status === 500) {
        console.error('Server Error:', xhr.responseJSON);
        Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'An unexpected error occurred. Please try again later.',
            confirmButtonColor: '#3085d6'
        });
    }
});

// Function to display validation errors
function displayValidationErrors(errors) {
    let errorMessage = '<ul class="mb-0">';
    $.each(errors, function(key, value) {
        errorMessage += '<li>' + value[0] + '</li>';
    });
    errorMessage += '</ul>';

    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: errorMessage,
        confirmButtonColor: '#3085d6'
    });
}

// Make displayValidationErrors globally available
window.displayValidationErrors = displayValidationErrors;

// ============================================
// DOM Ready - All initialization here
// ============================================
$(function() {
    console.log('RSCB Management System - Initializing...');

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        [...tooltipTriggerList].forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Initialize Bootstrap popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    if (popoverTriggerList.length > 0) {
        [...popoverTriggerList].forEach(popoverTriggerEl => {
            new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // ============================================
    // Sidebar Toggle - Multiple ways to ensure it works
    // ============================================

    // Method 1: Direct click handler
    $(document).on('click', '#sidebarToggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Sidebar toggle clicked');
        $('.sidebar').toggleClass('show');
    });

    // Method 2: If the button exists on page load
    const sidebarToggleBtn = document.getElementById('sidebarToggle');
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Sidebar toggle clicked (vanilla JS)');
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        });
    }

    // Mobile sidebar close when clicking outside
    $(document).on('click', function(e) {
        if ($(window).width() <= 992) {
            const sidebar = $('.sidebar');
            const toggleBtn = $('#sidebarToggle');

            if (sidebar.hasClass('show') &&
                !$(e.target).closest('.sidebar').length &&
                !$(e.target).closest('#sidebarToggle').length) {
                sidebar.removeClass('show');
            }
        }
    });

    // Close sidebar when a nav link is clicked (mobile)
    $('.sidebar .nav-link').on('click', function() {
        if ($(window).width() <= 992) {
            $('.sidebar').removeClass('show');
        }
    });

    // ============================================
    // Form change tracking
    // ============================================
    let formChanged = false;

    $(document).on('change input', 'form', function() {
        formChanged = true;
    });

    $(document).on('submit', 'form', function() {
        formChanged = false;
    });

    // Warn before leaving with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // ============================================
    // Notification functions
    // ============================================

    // Mark notification as read
    $(document).on('click', '.notification-item', function(e) {
        const notificationId = $(this).data('id');
        if (notificationId) {
            $.ajax({
                url: `/notifications/${notificationId}/read`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    });

    // Mark all notifications as read
    $(document).on('click', '.mark-all-read', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/notifications/mark-all-read',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).then(() => {
            $('.notification-item.unread').removeClass('unread');
            $('.notification-badge').hide();
        });
    });

    // ============================================
    // DataTables default configuration
    // ============================================
    if ($.fn.DataTable) {
        $.extend(true, $.fn.DataTable.defaults, {
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: '<i class="bi bi-chevron-double-left"></i>',
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                    last: '<i class="bi bi-chevron-double-right"></i>'
                }
            },
            responsive: true,
            pageLength: 25,
        });
    }

    console.log('RSCB Management System - Initialized successfully');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap version:', bootstrap.Tooltip.VERSION);
    console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');
});

// ============================================
// Console welcome message
// ============================================
console.log(`
╔══════════════════════════════════════════╗
║   Regional Sports Control Board (RSCB)  ║
║   Sports Management System v1.0         ║
╚══════════════════════════════════════════╝
`);
