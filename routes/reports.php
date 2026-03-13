<?php

use App\Http\Controllers\Reports\ReportController;
use Illuminate\Support\Facades\Route;

// Report exports & previews
Route::controller(ReportController::class)->group(function () {
    Route::get('/export', 'exportOptions')->name('export');
    Route::get('/preview', 'preview')->name('preview');
    Route::get('/pdf', 'generatePDF')->name('pdf');
    Route::get('/word', 'generateWord')->name('word');

    // Email
    Route::get('/email', 'emailForm')->name('email');
    Route::post('/email/send', 'sendEmail')->name('email.send');
});

