<?php

use App\Http\Controllers\GuideController;
use App\Http\Controllers\Settings\ApiSettingsController;
use App\Http\Controllers\Settings\BranchController;
use App\Http\Controllers\Settings\BrandingSettingController;
use App\Http\Controllers\Settings\CustomerController;
use App\Http\Controllers\Settings\DocumentationController;
use App\Http\Controllers\Settings\EmployeeController;
use App\Http\Controllers\Settings\FeatureController;
use App\Http\Controllers\Settings\RolesController;
use App\Http\Controllers\Settings\SiteController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Shared\ProtectedFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Settings Module
|--------------------------------------------------------------------------
*/

// Overview
Route::view('/', 'settings.index')->name('index');

// Feature management
Route::resource('features', FeatureController::class)
    ->except('show');

// User management
Route::resource('users', UserController::class)
    ->only(['index','create','store','edit','update','destroy']);

// User access settings routes
Route::get('users/{user}/access', [UserController::class, 'editAccess'])
    ->name('users.access');
Route::post('users/{user}/access', [UserController::class, 'updateAccess'])
    ->name('users.update-access');
Route::get('users/access-data', [UserController::class, 'getAccessData'])
    ->name('users.access-data');

// Customer management
Route::resource('customers', CustomerController::class)
    ->only(['index','create','store','edit','update','destroy']);

// Site management
Route::resource('sites', SiteController::class)
    ->only(['index','create','store','edit','update','destroy']);

// Employee management
Route::resource('employees', EmployeeController::class)
    ->only(['index','create','store','edit','update','destroy']);

// Branch management
Route::resource('branches', BranchController::class)
    ->only(['index','create','store','edit','update','destroy']);

// API Settings
Route::prefix('api-settings')->as('api.settings.')->group(function () {
    Route::get('/', [ApiSettingsController::class, 'index'])->name('index');
    Route::post('/regenerate-token', [ApiSettingsController::class, 'regenerateToken'])
        ->name('regenerate');
});

// Branding Settings
Route::get('branding', [BrandingSettingController::class, 'edit'])
    ->name('branding.index');
Route::put('branding', [BrandingSettingController::class, 'update'])
    ->name('branding.update');

// System Documentation
Route::get('/documentation', [DocumentationController::class, 'index'])
    ->name('documentation.index');
Route::get('/documentation/{filename}', [ProtectedFileController::class, 'show'])
    ->where('filename', '[A-Za-z0-9\-_\.]+\.(?:png|pdf)')
    ->name('documentation.file');
Route::get('/documentation/guides', [DocumentationController::class, 'guides'])
    ->name('documentation.guides.index');

// Guides (PDF) management
Route::resource('guides', GuideController::class)
    ->except('show');
Route::patch('guides/{guide}/share/regenerate', [GuideController::class, 'regenerateShareToken'])
    ->name('guides.regenerateShareToken');
Route::patch('guides/{guide}/share/revoke',   [GuideController::class, 'revokeShareToken'])
    ->name('guides.revokeShareToken');

// Roles management
Route::resource('roles', RolesController::class)
    ->except('show')
    ->names([
        'index'   => 'roles.index',
        'create'  => 'roles.create',
        'store'   => 'roles.store',
        'edit'    => 'roles.edit',
        'update'  => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);
