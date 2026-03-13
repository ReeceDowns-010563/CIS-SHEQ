<?php

use Illuminate\Support\Facades\Route;

// Redirect root → login
Route::redirect('/', '/login');

// Timegate sync routes (for cron jobs) - Updated method names
Route::get('/sync-branches', [App\Http\Controllers\TimegateController::class, 'synchronizeBranches']);
Route::get('/sync-sites', [App\Http\Controllers\TimegateController::class, 'synchronizeSites']);
Route::get('/sync-employees', [App\Http\Controllers\TimegateController::class, 'synchronizeEmployees']);
Route::get('/sync-all', [App\Http\Controllers\TimegateController::class, 'synchronizeAllEntities']);

// Employee search endpoint
Route::get('/find-employee', [App\Http\Controllers\TimegateController::class, 'searchEmployeeInBranch']);

// Test employee creation (keep for now)
Route::get('/create-employee', [App\Http\Controllers\TestEmployeeController::class, 'createEmployee']);

// Dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Dashboard layout management routes
Route::middleware('auth')->group(function () {
    Route::post('/dashboard/save-layout', [App\Http\Controllers\DashboardController::class, 'saveDashboardLayout'])
        ->name('dashboard.save-layout');
    Route::post('/dashboard/reset-layout', [App\Http\Controllers\DashboardController::class, 'resetDashboardLayout'])
        ->name('dashboard.reset-layout');
});

// Auth-protected modules
Route::middleware('auth')->group(function () {

    // Profile
    Route::prefix('profile')
        ->as('profile.')
        ->group(base_path('routes/profile.php'));

    // Settings
    Route::prefix('settings')
        ->as('settings.')
        ->middleware('feature:settings')
        ->group(function () {
            // Load existing settings routes
            require base_path('routes/settings.php');

            // Email Settings Routes
            require base_path('routes/emails.php');
        });

    // Complaints
    Route::prefix('complaints')
        ->as('complaints.')
        ->middleware('feature:complaints')
        ->group(base_path('routes/complaints.php'));

    // Incidents
    Route::prefix('incidents')
        ->as('incidents.')
        ->middleware('feature:incidents')
        ->group(base_path('routes/incidents.php'));

    // Reports (exports, previews, email)
    Route::prefix('reports')
        ->as('reports.')
        ->middleware('feature:reports')
        ->group(base_path('routes/reports.php'));

    // Charts
    Route::prefix('charts')
        ->as('charts.')
        ->middleware('feature:reports')
        ->group(base_path('routes/charts.php'));
});

// Public / Shared Guides
Route::get('/guides/{uuid}', [App\Http\Controllers\GuideController::class, 'show'])
    ->name('guides.show');

Route::get('/guides/{uuid}/shared/{token}', [App\Http\Controllers\GuideController::class, 'shared'])
    ->name('guides.shared');

// Authentication Routes
require __DIR__ . '/auth.php';
