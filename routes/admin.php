<?php
// routes/admin.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\AirportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RegistrationApprovalController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:employee', 'force.password.change', 'check.employee.status'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('dashboard');
    Route::get('/regional-dashboard', [DashboardController::class, 'regionalDashboard'])->name('regional.dashboard');
    Route::get('/airport-dashboard', [DashboardController::class, 'airportDashboard'])->name('airport.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Region Management
    |--------------------------------------------------------------------------
    */
    Route::resource('regions', RegionController::class);

    /*
    |--------------------------------------------------------------------------
    | Airport Management
    |--------------------------------------------------------------------------
    */
    Route::resource('airports', AirportController::class);

    /*
    |--------------------------------------------------------------------------
    | Employee Management
    |--------------------------------------------------------------------------
    */
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])
        ->name('employees.toggle-status');
    Route::post('employees/{id}/reset-password', [EmployeeController::class, 'resetPassword'])
        ->name('employees.reset-password');
    Route::get('employees-import', [EmployeeController::class, 'showImportForm'])
        ->name('employees.import');
    Route::post('employees-import', [EmployeeController::class, 'import']);
    Route::get('employees-export', [EmployeeController::class, 'export'])
        ->name('employees.export');
    Route::get('employees-template', [EmployeeController::class, 'downloadTemplate'])
        ->name('employees.template');

    /*
    |--------------------------------------------------------------------------
    | Event Management
    |--------------------------------------------------------------------------
    */
    Route::resource('events', EventController::class);
    Route::post('events/{id}/publish', [EventController::class, 'publish'])
        ->name('events.publish');
    Route::post('events/{id}/cancel', [EventController::class, 'cancel'])
        ->name('events.cancel');
    Route::get('events/{id}/registrations', [EventController::class, 'registrations'])
        ->name('events.registrations');
    Route::get('events/{id}/export', [EventController::class, 'exportRegistrations'])
        ->name('events.export');

    /*
    |--------------------------------------------------------------------------
    | Form Builder
    |--------------------------------------------------------------------------
    */
    Route::prefix('forms')->name('forms.')->group(function () {
        Route::get('/builder/{id?}', [FormBuilderController::class, 'builder'])
            ->name('builder');
        Route::post('/save-template', [FormBuilderController::class, 'saveTemplate'])
            ->name('save-template');
        Route::get('/preview/{id}', [FormBuilderController::class, 'preview'])
            ->name('preview');
        Route::get('/templates', [FormBuilderController::class, 'templatesList'])
            ->name('templates');
    });

    /*
    |--------------------------------------------------------------------------
    | Registration Management (Approval)
    |--------------------------------------------------------------------------
    */
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/pending', [RegistrationApprovalController::class, 'pendingApprovals'])
            ->name('pending');
        Route::get('/', [RegistrationApprovalController::class, 'index'])->name('index');
        Route::get('/{id}', [RegistrationApprovalController::class, 'showRegistration'])
            ->name('show');
        Route::post('/{id}/approve', [RegistrationApprovalController::class, 'approve'])
            ->name('approve');
        Route::post('/{id}/reject', [RegistrationApprovalController::class, 'reject'])
            ->name('reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Document Verification
    |--------------------------------------------------------------------------
    */
    Route::post('/documents/{documentId}/verify', [RegistrationApprovalController::class, 'verifyDocument'])
        ->name('documents.verify');

    /*
    |--------------------------------------------------------------------------
    | Reports & Analytics
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::post('/generate/{registration}', [CertificateController::class, 'generate'])->name('generate');
        Route::post('/bulk-generate', [CertificateController::class, 'bulkGenerate'])->name('bulk-generate');
        Route::get('/download/{id}', [CertificateController::class, 'download'])->name('download');
        Route::get('/preview/{registration}', [CertificateController::class, 'preview'])->name('preview');
        Route::post('/customize-template', [CertificateController::class, 'customizeTemplate'])->name('customize-template');
    });

    /*
    |--------------------------------------------------------------------------
    | Announcements
    |--------------------------------------------------------------------------
    */
    Route::resource('announcements', AnnouncementController::class);
    Route::post('announcements/{id}/publish', [AnnouncementController::class, 'publish'])
        ->name('announcements.publish');

    /*
    |--------------------------------------------------------------------------
    | Gallery
    |--------------------------------------------------------------------------
    */
    Route::resource('gallery', GalleryController::class);
    Route::post('gallery/upload-multiple', [GalleryController::class, 'uploadMultiple'])
        ->name('gallery.upload-multiple');

    /*
    |--------------------------------------------------------------------------
    | Roles & Permissions
    |--------------------------------------------------------------------------
    */
    Route::resource('roles', RoleController::class);
    Route::post('/assign-role', [RoleController::class, 'assignRole'])->name('roles.assign');

    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */
    Route::get('/audit-logs', function () {
        return view('admin.audit-logs');
    })->name('audit-logs');

});

/*
|--------------------------------------------------------------------------
| API Routes for AJAX (within admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:employee'])->prefix('api')->name('api.')->group(function () {
    Route::get('airports-by-region/{region}', [EmployeeController::class, 'getAirportsByRegion'])
        ->name('airports.by.region');
    Route::post('check-employee-id', [EmployeeController::class, 'checkEmployeeId'])
        ->name('employee.check.id');
});
