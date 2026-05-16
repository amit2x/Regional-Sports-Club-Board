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

// Import DataTables core
import 'datatables.net-bs5';

// Import DataTables extensions
import 'datatables.net-buttons-bs5';
import 'datatables.net-responsive-bs5';

// Import Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// CSRF Token setup for AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Global AJAX error handler
$(document).ajaxError(function(event, xhr, settings, error) {
    console.error('AJAX Error:', {
        status: xhr.status,
        statusText: xhr.statusText,
        responseJSON: xhr.responseJSON,
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
        // Validation errors handled by individual forms
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

// Global AJAX beforeSend handler
$(document).ajaxSend(function(event, xhr, settings) {
    // Add loading indicator for non-GET requests
    if (settings.type !== 'GET') {
        // Could add a global loading indicator here
    }
});

// Global AJAX complete handler
$(document).ajaxComplete(function(event, xhr, settings) {
    // Remove loading indicator if exists
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

// Initialize Bootstrap components
$(document).ready(function() {
    // Initialize all tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
});

// Sidebar toggle functionality
$(document).on('click', '#sidebarToggle', function() {
    $('.sidebar').toggleClass('show');
    $('.main-content').toggleClass('expanded');
});

// Mobile sidebar close when clicking outside
$(document).on('click', function(e) {
    if ($(window).width() <= 768) {
        if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('#sidebarToggle').length) {
            $('.sidebar').removeClass('show');
        }
    }
});

// Confirm before leaving page with unsaved changes
let formChanged = false;

$(document).on('change input', 'form', function() {
    formChanged = true;
});

$(document).on('submit', 'form', function() {
    formChanged = false;
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
    }
});

// Export global functions
window.displayValidationErrors = displayValidationErrors;

// Console welcome message
console.log(`
    ╔══════════════════════════════════════════╗
    ║   Regional Sports Control Board (RSCB)  ║
    ║   Sports Management System v1.0         ║
    ╚══════════════════════════════════════════╝
`);
