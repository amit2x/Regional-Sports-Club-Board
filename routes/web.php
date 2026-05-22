<?php
// routes/web.php (Complete file)

use App\Http\Controllers\Admin\AirportController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RegistrationApprovalController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\EmployeeAuthController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserManualController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/events', [WebsiteController::class, 'events'])->name('website.events');
Route::get('/events/{slug}', [WebsiteController::class, 'eventDetails'])->name('website.event-details');
Route::get('/announcements', [WebsiteController::class, 'announcements'])->name('website.announcements');
Route::get('/announcements/{id}', [WebsiteController::class, 'announcementDetails'])->name('website.announcement-details');
Route::get('/gallery', [WebsiteController::class, 'gallery'])->name('website.gallery');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('website.contact');
Route::post('/contact', [WebsiteController::class, 'submitContact'])->name('website.contact.submit');
Route::get('/winners', [WebsiteController::class, 'winners'])->name('website.winners');
Route::get('/downloads', [WebsiteController::class, 'downloads'])->name('website.downloads');
Route::get('/search', [WebsiteController::class, 'search'])->name('website.search');


// Static Pages
Route::get('/faq', [StaticPageController::class, 'faq'])->name('website.faq');
Route::get('/terms', [StaticPageController::class, 'terms'])->name('website.terms');
Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('website.privacy');
Route::get('/about', [StaticPageController::class, 'about'])->name('website.about');
Route::get('/help', [StaticPageController::class, 'help'])->name('website.help');
Route::get('/sitemap', [StaticPageController::class, 'sitemap'])->name('website.sitemap');
Route::get('/accessibility', [StaticPageController::class, 'accessibility'])->name('website.accessibility');
Route::get('/disclaimer', [StaticPageController::class, 'disclaimer'])->name('website.disclaimer');
Route::get('/copyright', [StaticPageController::class, 'copyright'])->name('website.copyright');
Route::get('/feedback', [StaticPageController::class, 'feedback'])->name('website.feedback');
Route::post('/feedback', [StaticPageController::class, 'submitFeedback'])->name('website.feedback.submit');
// PWA
Route::get('/manifest.json', [WebsiteController::class, 'manifest'])->name('manifest');
Route::get('/service-worker.js', [WebsiteController::class, 'serviceWorker'])->name('service-worker');
Route::view('/offline', 'website.offline')->name('offline');

// Certificate Verification (Public)
Route::get('/verify-certificate', [CertificateController::class, 'verify'])->name('certificates.verify');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->name('employee.')->group(function () {

    // Login Routes (Redirect if already authenticated)
    Route::middleware('guest:employee')->group(function () {
        Route::get('/login', [EmployeeAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [EmployeeAuthController::class, 'login']);

        // Google OAuth
        Route::get('/auth/google', [EmployeeAuthController::class, 'redirectToGoogle'])->name('auth.google');
        Route::get('/auth/google/callback', [EmployeeAuthController::class, 'handleGoogleCallback']);

        // Forgot Password
        Route::get('/forgot-password', [EmployeeAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [EmployeeAuthController::class, 'sendResetLink'])->name('password.email');
    });

    // Authenticated Routes
    Route::middleware('auth:employee')->group(function () {
        // Logout
        Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('logout');

        // Change Password
        Route::get('/change-password', [EmployeeAuthController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('/change-password', [EmployeeAuthController::class, 'changePassword']);
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:employee', 'force.password.change', 'check.employee.status'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('dashboard');

        // Regions
        Route::resource('regions', RegionController::class);

        // Airports
        Route::resource('airports', AirportController::class);

        // Roles & Permissions
        Route::resource('roles', RoleController::class);
        Route::post('/assign-role', [RoleController::class, 'assignRole'])->name('roles.assign');

        // Audit Logs
        Route::get('/audit-logs', function() {
            return view('admin.audit-logs');
        })->name('audit-logs');
    });

    /*
    |--------------------------------------------------------------------------
    | Regional & Airport Secretary Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin|regional_sports_secretary|airport_sports_secretary'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        // Dashboard
        Route::get('/regional-dashboard', [DashboardController::class, 'regionalDashboard'])->name('regional.dashboard');
        Route::get('/airport-dashboard', [DashboardController::class, 'airportDashboard'])->name('airport.dashboard');

        // Employees
        Route::resource('employees', EmployeeController::class);
        Route::post('employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
        Route::post('employees/{id}/reset-password', [EmployeeController::class, 'resetPassword'])->name('employees.reset-password');
        Route::get('employees-import', [EmployeeController::class, 'showImportForm'])->name('employees.import');
        Route::post('employees-import', [EmployeeController::class, 'import']);
        Route::get('employees-export', [EmployeeController::class, 'export'])->name('employees.export');
        Route::get('employees-template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');

        // Events
        Route::resource('events', EventController::class);
        Route::post('events/{id}/publish', [EventController::class, 'publish'])->name('events.publish');
        Route::post('events/{id}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
        Route::get('events/{id}/registrations', [EventController::class, 'registrations'])->name('events.registrations');

        // Forms
        Route::prefix('forms')->name('forms.')->group(function () {
            Route::get('/builder/{id?}', [FormBuilderController::class, 'builder'])->name('builder');
            Route::post('/save-template', [FormBuilderController::class, 'saveTemplate'])->name('save-template');
            Route::get('/preview/{id}', [FormBuilderController::class, 'preview'])->name('preview');
            Route::get('/templates', [FormBuilderController::class, 'templatesList'])->name('templates');
        });

        // Registrations
        Route::get('/registrations/pending', [RegistrationApprovalController::class, 'pendingApprovals'])->name('registrations.pending');
        Route::get('/registrations/{id}', [RegistrationApprovalController::class, 'showRegistration'])->name('registrations.show');
        Route::post('/registrations/{id}/approve', [RegistrationApprovalController::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{id}/reject', [RegistrationApprovalController::class, 'reject'])->name('registrations.reject');
        Route::post('/documents/{documentId}/verify', [RegistrationApprovalController::class, 'verifyDocument'])->name('documents.verify');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/participation', [ReportController::class, 'participationReport'])->name('participation');
            Route::get('/events', [ReportController::class, 'eventReport'])->name('events');
            Route::get('/region-analytics', [ReportController::class, 'regionAnalytics'])->name('region-analytics');
            Route::get('/gender-analytics', [ReportController::class, 'genderAnalytics'])->name('gender-analytics');
            Route::get('/sports-category', [ReportController::class, 'sportsCategoryAnalytics'])->name('sports-category');
            Route::get('/employee-history', [ReportController::class, 'employeeHistory'])->name('employee-history');
            Route::get('/dashboard-analytics', [ReportController::class, 'dashboardAnalytics'])->name('dashboard-analytics');
        });

        // Certificates
        Route::prefix('certificates')->name('certificates.')->group(function () {
            Route::get('/', [CertificateController::class, 'index'])->name('index');
            Route::post('/generate/{registration}', [CertificateController::class, 'generate'])->name('generate');
            Route::post('/bulk-generate', [CertificateController::class, 'bulkGenerate'])->name('bulk-generate');
            Route::get('/download/{id}', [CertificateController::class, 'download'])->name('download');
            Route::get('/preview/{registration}', [CertificateController::class, 'preview'])->name('preview');
            Route::post('/customize-template', [CertificateController::class, 'customizeTemplate'])->name('customize-template');
        });

        // Announcements
        Route::resource('announcements', AnnouncementController::class);
        Route::post('announcements/{id}/publish', [AnnouncementController::class, 'publish'])->name('announcements.publish');

        // Gallery
        Route::resource('gallery', GalleryController::class);
        Route::post('gallery/upload-multiple', [GalleryController::class, 'uploadMultiple'])->name('gallery.upload-multiple');
    });

    /*
    |--------------------------------------------------------------------------
    | Employee Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:employee'])->prefix('employee-portal')->name('employee.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('dashboard');
        Route::get('/events/available', [EventRegistrationController::class, 'availableEvents'])->name('events.available');
        Route::get('/events/{eventId}/register', [EventRegistrationController::class, 'showRegistrationForm'])->name('events.register');
        Route::post('/events/{eventId}/register', [EventRegistrationController::class, 'submitRegistration'])->name('events.submit-registration');
        Route::get('/registrations', [EventRegistrationController::class, 'myRegistrations'])->name('registrations.index');
        Route::get('/registrations/{id}', [EventRegistrationController::class, 'showRegistration'])->name('registrations.show');
        Route::post('/registrations/{id}/withdraw', [EventRegistrationController::class, 'withdrawRegistration'])->name('registrations.withdraw');
        Route::get('/employee-profile', function() {
            return view('employee.profile.show', ['employee' => auth()->guard('employee')->user()]);
            })->name('profile.show');

        /*
    |--------------------------------------------------------------------------
    | Notification Routes for employee specific
    |--------------------------------------------------------------------------
    */
    Route::get('/my-notifications', [NotificationController::class, 'index'])->name('notifications.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Notification Routes
    |--------------------------------------------------------------------------
    */



    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::get('/notifications/{id}/click', [NotificationController::class, 'handleClick'])->name('notifications.handle-click');


    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', function() {
        return view('profile.show', ['employee' => auth()->guard('employee')->user()]);
    })->name('profile.show');
    Route::put('/profile', [EmployeeAuthController::class, 'updateProfile'])->name('profile.update');
});


// User Manual Routes
Route::get('/manual', [UserManualController::class, 'index'])->name('manual');
Route::get('/manual/{section}', [UserManualController::class, 'section'])->name('manual.section');
Route::get('/manual/download/pdf', [UserManualController::class, 'downloadPdf'])->name('manual.download');

/*
|--------------------------------------------------------------------------
| API Routes for AJAX
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:employee'])->prefix('api')->name('api.')->group(function () {
    Route::get('airports-by-region/{region}', [EmployeeController::class, 'getAirportsByRegion'])->name('airports.by.region');
    Route::post('check-employee-id', [EmployeeController::class, 'checkEmployeeId'])->name('employee.check.id');
});

// Admin routes
require __DIR__.'/admin.php';
