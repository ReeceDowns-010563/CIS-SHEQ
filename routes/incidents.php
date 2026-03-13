<?php

use App\Http\Controllers\Incidents\IncidentController;
use App\Http\Controllers\Incidents\IncidentFormController;
use App\Http\Controllers\Incidents\IncidentStatusController;
use App\Http\Controllers\Incidents\IncidentCommentController;
use App\Http\Controllers\Incidents\IncidentReportController;
use App\Http\Controllers\Incidents\IncidentAttachmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Incident Management Routes
|--------------------------------------------------------------------------
|
| Routes for incident reporting, investigation, and management.
| Includes CRUD operations, status management, comments, and reporting.
|
| Route Structure:
| - Main incident routes: listing, viewing, basic operations
| - Form routes: create, edit, update incidents
| - Status routes: status changes, assignments, archiving
| - Comment routes: add, view, delete comments
| - Report routes: professional reports and PDF generation
| - Attachment routes: upload, download, remove attachments
|
*/

// Incident form routes - Create route MUST come before {incident} parameter routes
Route::controller(IncidentFormController::class)->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{incident}/edit', 'edit')->name('edit');
    Route::put('/{incident}', 'update')->name('update');
});

// Main incident routes - Core incident management functionality
Route::controller(IncidentController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/register', 'register')->name('register'); // Alias for index
    Route::get('/my-investigations', 'myInvestigations')->name('my-investigations');
    Route::get('/analytics', 'analytics')->name('analytics');
    Route::get('/{incident}', 'show')->name('show');
    Route::delete('/{incident}', 'destroy')->name('destroy');
});

// Status management routes - Handle incident lifecycle and assignments
Route::controller(IncidentStatusController::class)->group(function () {
    Route::patch('/{incident}/status', 'updateStatus')->name('status.update');
    Route::patch('/{incident}/assign', 'assign')->name('assignment.update');
    Route::patch('/{incident}/archive', 'archive')->name('archive');
    Route::patch('/{incident}/unarchive', 'unarchive')->name('unarchive');
    Route::patch('/{incident}/status-with-corrective-actions', 'updateStatusWithCorrectiveActions')->name('status-with-corrective-actions.update');
});

// Attachment management routes - Handle file attachments
Route::controller(IncidentAttachmentController::class)->group(function () {
    Route::get('/{incident}/attachments/{index}', 'viewAttachment')->name('view-attachment');
    Route::get('/{incident}/attachments/{index}/download', 'downloadAttachment')->name('download-attachment');
    Route::delete('/{incident}/attachments/{index}', 'removeAttachment')->name('remove-attachment');
});

// Comment management routes - Collaborative investigation notes
Route::controller(IncidentCommentController::class)->group(function () {
    Route::get('/{incident}/comments', 'index')->name('comments.index');
    Route::post('/{incident}/comments', 'store')->name('comments.store');
    Route::delete('/{incident}/comments/{comment}', 'destroy')->name('comments.destroy');
});

// Report routes - Professional reports and documentation
Route::controller(IncidentReportController::class)->prefix('reports')->name('reports.')->group(function () {
    Route::get('/{incident}', 'show')->name('show');
    Route::get('/{incident}/pdf', 'downloadPdf')->name('pdf');
});
