<?php

use App\Http\Controllers\Settings\{
    EmailTemplateController,
    EmailAuditController,
    EmailTestController
};

// Email Templates Management - Note the 'templates' part to match your view
Route::prefix('emails/templates')->as('emails.templates.')->group(function () {
    Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
    Route::get('/create', [EmailTemplateController::class, 'create'])->name('create');
    Route::post('/', [EmailTemplateController::class, 'store'])->name('store');
    Route::get('/{emailTemplate}', [EmailTemplateController::class, 'show'])->name('show');
    Route::get('/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('edit');
    Route::put('/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('update');
    Route::delete('/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('destroy');

    // Preview functionality
    Route::get('/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])->name('preview');

    // Test email functionality
    Route::post('/{emailTemplate}/send-test', [EmailTemplateController::class, 'sendTest'])->name('send-test');

    // Clone template
    Route::post('/{emailTemplate}/clone', [EmailTemplateController::class, 'clone'])->name('clone');

    // Toggle status
    Route::post('/{emailTemplate}/toggle-status', [EmailTemplateController::class, 'toggleStatus'])->name('toggle-status');
});

// Keep the existing email-audit and email-test routes as they are
Route::prefix('email-audit')->as('email-audit.')->group(function () {
    Route::get('/', [EmailAuditController::class, 'index'])->name('index');
    Route::get('/{emailAudit}', [EmailAuditController::class, 'show'])->name('show');
    Route::delete('/{emailAudit}', [EmailAuditController::class, 'destroy'])->name('destroy');

    // Bulk actions
    Route::post('/bulk-delete', [EmailAuditController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/cleanup', [EmailAuditController::class, 'cleanup'])->name('cleanup');

    // Export functionality
    Route::get('/export/{format}', [EmailAuditController::class, 'export'])->name('export')
        ->where('format', 'csv|excel|pdf');
});

// Email Testing Tools
Route::prefix('email-test')->as('email-test.')->group(function () {
    Route::get('/', [EmailTestController::class, 'index'])->name('index');
    Route::post('/send', [EmailTestController::class, 'send'])->name('send');
    Route::get('/limits', [EmailTestController::class, 'limits'])->name('limits');
    Route::post('/reset-limits', [EmailTestController::class, 'resetLimits'])->name('reset-limits');
});
