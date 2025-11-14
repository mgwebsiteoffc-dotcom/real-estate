<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

    // Leads Management
Route::resource('leads', \App\Http\Controllers\LeadController::class);
Route::get('leads-kanban', [\App\Http\Controllers\LeadController::class, 'kanban'])->name('leads.kanban');
Route::post('leads/{lead}/update-status', [\App\Http\Controllers\LeadController::class, 'updateStatus'])->name('leads.update-status');

    
Route::resource('properties', \App\Http\Controllers\PropertyController::class);
// Projects Management
Route::resource('projects', \App\Http\Controllers\ProjectController::class);
Route::resource('tasks', \App\Http\Controllers\TaskController::class);
Route::post('tasks/{task}/complete', [\App\Http\Controllers\TaskController::class, 'complete'])->name('tasks.complete');
// User Management (Company Admin only)
Route::resource('users', \App\Http\Controllers\UserController::class);

Route::get('settings/company', [\App\Http\Controllers\CompanySettingsController::class, 'edit'])->name('settings.company.edit');
    Route::post('settings/company', [\App\Http\Controllers\CompanySettingsController::class, 'update'])->name('settings.company.update');
    // User Profile
    Route::get('settings/profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::post('settings/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('settings.profile.update');
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
       Route::get('reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/leads/excel', [App\Http\Controllers\ReportsController::class, 'exportLeadsExcel'])->name('reports.leads.excel');
    Route::get('reports/leads/pdf', [App\Http\Controllers\ReportsController::class, 'exportLeadsPdf'])->name('reports.leads.pdf');
        Route::get('integrations', [\App\Http\Controllers\IntegrationController::class, 'index'])->name('integrations.index');
    Route::post('integrations', [\App\Http\Controllers\IntegrationController::class, 'update'])->name('integrations.update');
});
Route::middleware(['auth'])->resource('automations', \App\Http\Controllers\AutomationController::class);
