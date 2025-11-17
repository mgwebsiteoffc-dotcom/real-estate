<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TaskController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
     // Companies Management (Super Admin only)
    Route::resource('companies', \App\Http\Controllers\CompanyController::class);
    
    // Packages Management (Super Admin only)
Route::resource('packages', \App\Http\Controllers\PackageController::class);

// Trash routes for packages
Route::get('packages/trash/view', [\App\Http\Controllers\PackageController::class, 'trashed'])->name('packages.trashed');
Route::post('packages/{id}/restore', [\App\Http\Controllers\PackageController::class, 'restore'])->name('packages.restore');
Route::delete('packages/{id}/force', [\App\Http\Controllers\PackageController::class, 'forceDelete'])->name('packages.force-delete');


     // ADD THESE NEW ROUTES FOR IMPORT
    Route::get('leads/import', [App\Http\Controllers\LeadImportController::class, 'showImportForm'])->name('leads.import');
    Route::post('leads/import/process', [App\Http\Controllers\LeadImportController::class, 'import'])->name('leads.import.process');
    Route::get('leads/import/template', [App\Http\Controllers\LeadImportController::class, 'downloadTemplate'])->name('leads.import.template');
    // Leads Management
Route::resource('leads', \App\Http\Controllers\LeadController::class);
Route::get('leads-kanban', [\App\Http\Controllers\LeadController::class, 'kanban'])->name('leads.kanban');
Route::post('leads/{lead}/update-status', [\App\Http\Controllers\LeadController::class, 'updateStatus'])->name('leads.update-status');

    
Route::resource('properties', \App\Http\Controllers\PropertyController::class);
// Projects Management
Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    
    // NEW ROUTES
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/{task}/comment', [TaskController::class, 'addComment'])->name('tasks.addComment');
// User Management (Company Admin only)
Route::resource('users', \App\Http\Controllers\UserController::class);

Route::get('settings/company', [\App\Http\Controllers\CompanySettingsController::class, 'edit'])->name('settings.company.edit');
    Route::post('settings/company', [\App\Http\Controllers\CompanySettingsController::class, 'update'])->name('settings.company.update');
    // User Profile
    Route::get('settings/profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::post('settings/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('settings.profile.update');
   Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread', [App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
       Route::get('reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/leads/excel', [App\Http\Controllers\ReportsController::class, 'exportLeadsExcel'])->name('reports.leads.excel');
    Route::get('reports/leads/pdf', [App\Http\Controllers\ReportsController::class, 'exportLeadsPdf'])->name('reports.leads.pdf');
        Route::get('integrations', [\App\Http\Controllers\IntegrationController::class, 'index'])->name('integrations.index');
    Route::post('integrations', [\App\Http\Controllers\IntegrationController::class, 'update'])->name('integrations.update');

        Route::get('integrations/google/auth', [App\Http\Controllers\IntegrationController::class, 'googleCalendarAuth'])
        ->name('integrations.google.auth');
    Route::get('integrations/google/callback', [App\Http\Controllers\IntegrationController::class, 'googleCalendarCallback'])
        ->name('integrations.google.callback');

      // Activity routes
    Route::post('leads/{lead}/activities', [App\Http\Controllers\ActivityController::class, 'store'])
        ->name('leads.activities.store');
    
    // Follow-up routes
    Route::post('leads/{lead}/follow-ups', [App\Http\Controllers\FollowUpController::class, 'store'])
        ->name('leads.follow-ups.store');
    Route::patch('follow-ups/{followUp}/complete', [App\Http\Controllers\FollowUpController::class, 'complete'])
        ->name('follow-ups.complete');
        Route::patch('leads/{lead}/notes', [App\Http\Controllers\LeadController::class, 'updateNotes'])
    ->name('leads.notes.update');

      Route::get('calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents'])->name('calendar.events');
    Route::post('calendar/appointments', [App\Http\Controllers\CalendarController::class, 'store'])->name('calendar.store');
    Route::put('calendar/appointments/{appointment}', [App\Http\Controllers\CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('calendar/appointments/{appointment}', [App\Http\Controllers\CalendarController::class, 'destroy'])->name('calendar.destroy');

      // Portal Integration Routes
    Route::get('portals', [App\Http\Controllers\PortalIntegrationController::class, 'index'])->name('portals.index');
    Route::post('portals', [App\Http\Controllers\PortalIntegrationController::class, 'store'])->name('portals.store');
    Route::patch('portals/{portalConfig}/toggle', [App\Http\Controllers\PortalIntegrationController::class, 'toggle'])->name('portals.toggle');
    Route::delete('portals/{portalConfig}', [App\Http\Controllers\PortalIntegrationController::class, 'destroy'])->name('portals.destroy');
    
    // Property Sync Routes
    Route::post('properties/{property}/sync', [App\Http\Controllers\PortalIntegrationController::class, 'syncProperty'])->name('properties.sync');
    Route::get('properties/{property}/sync-status', [App\Http\Controllers\PortalIntegrationController::class, 'syncStatus'])->name('properties.sync-status');
    Route::delete('portal-syncs/{sync}/remove', [App\Http\Controllers\PortalIntegrationController::class, 'removeSync'])->name('portal-syncs.remove');
    Route::resource('microsites', App\Http\Controllers\MicrositeController::class);
    Route::post('microsites/{microsite}/publish', [App\Http\Controllers\MicrositeController::class, 'publish'])->name('microsites.publish');
    Route::post('microsites/{microsite}/unpublish', [App\Http\Controllers\MicrositeController::class, 'unpublish'])->name('microsites.unpublish');
    Route::get('microsites/{microsite}/analytics', [App\Http\Controllers\MicrositeController::class, 'analytics'])->name('microsites.analytics');
});
Route::middleware(['auth'])->resource('automations', \App\Http\Controllers\AutomationController::class);
// Public microsite routes (no auth)
Route::get('site/{slug}', [App\Http\Controllers\MicrositeController::class, 'show'])->name('microsite.show');
Route::post('site/{slug}/capture', [App\Http\Controllers\MicrositeController::class, 'captureLead'])->name('microsite.capture');