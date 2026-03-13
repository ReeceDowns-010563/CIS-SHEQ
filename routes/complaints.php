<?php

use App\Http\Controllers\Complaints\ComplaintController;
use App\Http\Controllers\Complaints\ComplaintExportController;
use App\Http\Controllers\Complaints\ComplaintManagementController;
use App\Http\Controllers\Complaints\ComplaintStatusController;
use App\Http\Controllers\Complaints\ComplaintInvestigationController;
use App\Http\Controllers\Complaints\ComplaintCommentController;
use Illuminate\Support\Facades\Route;

// Core CRUD routes
Route::controller(ComplaintController::class)->group(function () {
    // Create & store
    Route::get('/', 'create')->name('create');
    Route::post('/', 'store')->name('store');

    // Edit & update
    Route::get('/{complaint}/edit', 'edit')->name('edit');
    Route::put('/{complaint}', 'update')->name('update');
    Route::delete('/{complaint}', 'destroy')->name('destroy');

    // Unarchive (admin only)
    Route::patch('/{complaint}/unarchive', 'unarchive')->name('unarchive');
});

// Export routes
Route::controller(ComplaintExportController::class)->group(function () {
    Route::get('/download', 'form')->name('download');
    Route::post('/export', 'export')->name('export');
});

// Management routes
Route::controller(ComplaintManagementController::class)->group(function () {
    Route::get('/manage', 'index')->name('manage');
});

// Status & assignment routes
Route::controller(ComplaintStatusController::class)->group(function () {
    // AJAX routes (keep for JavaScript enhancement)
    Route::patch('/{complaint}/status', 'updateStatus')->name('updateStatus');
    Route::patch('/{complaint}/assignment', 'updateAssignment')->name('updateAssignment');

    // PHP form routes
    Route::post('/{complaint}/status', 'updateStatusForm')->name('updateStatus.form');
    Route::post('/{complaint}/assignment', 'updateAssignmentForm')->name('updateAssignment.form');
});

// Investigation routes
Route::controller(ComplaintInvestigationController::class)->group(function () {
    Route::get('/my-investigations', 'index')->name('my-investigations');
    Route::get('/{complaint}/edit-investigation', 'edit')->name('edit-investigation');
    Route::delete('/{complaint}/archive-from-investigations', 'archive')->name('archive-from-investigations');
});

// Comment routes
Route::prefix('{complaint}/comments')->name('comments.')->group(function () {
    Route::get('/', [ComplaintCommentController::class, 'index'])->name('index');
    Route::post('/', [ComplaintCommentController::class, 'store'])->name('store');
    Route::delete('/{comment}', [ComplaintCommentController::class, 'destroy'])->name('destroy');
});
