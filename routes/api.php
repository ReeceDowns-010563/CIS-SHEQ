<?php

use App\Http\Controllers\Api\ComplaintApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\IncidentReportApiController;
use App\Http\Controllers\Api\SiteApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Health check endpoint (no auth required)
Route::get('/health', [HealthController::class, 'check']);

// V1 API Routes
Route::prefix('v1')->group(function () {
    // Health check endpoint (no auth required)
    Route::get('/health', [HealthController::class, 'check']);

    // Protected routes requiring authentication
    Route::middleware('auth:sanctum')->group(function () {
        // Complaints endpoints
        Route::prefix('complaints')->group(function () {
            Route::get('/', [ComplaintApiController::class, 'index']);
            Route::get('/statistics', [ComplaintApiController::class, 'statistics']);
            Route::get('/{complaint}', [ComplaintApiController::class, 'show']);
            Route::post('/', [ComplaintApiController::class, 'store']);
            Route::put('/{complaint}', [ComplaintApiController::class, 'update']);
            Route::delete('/{complaint}', [ComplaintApiController::class, 'destroy']);
        });

        // Incident Reports endpoints
        Route::prefix('incident-reports')->group(function () {
            Route::get('/', [IncidentReportApiController::class, 'index']);
            Route::get('/{incidentReport}', [IncidentReportApiController::class, 'show']);
            Route::post('/', [IncidentReportApiController::class, 'store']);
            Route::put('/{incidentReport}', [IncidentReportApiController::class, 'update']);
            Route::delete('/{incidentReport}', [IncidentReportApiController::class, 'destroy']);
        });

        // Customers endpoints
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerApiController::class, 'index']);
            Route::get('/{customer}', [CustomerApiController::class, 'show']);
            Route::put('/{customer}', [CustomerApiController::class, 'update']);
            Route::delete('/{customer}', [CustomerApiController::class, 'destroy']);
        });

        // Sites endpoints
        Route::prefix('sites')->group(function () {
            Route::get('/', [SiteApiController::class, 'index']);
            Route::get('/{site}', [SiteApiController::class, 'show']);
            Route::post('/', [SiteApiController::class, 'store']);
            Route::put('/{site}', [SiteApiController::class, 'update']);
            Route::delete('/{site}', [SiteApiController::class, 'destroy']);
        });
    });
});
