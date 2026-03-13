<?php

use App\Http\Controllers\Charts\ChartsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Charts Module
|--------------------------------------------------------------------------
|
| Routed under /charts, protected by the 'feature:reports' middleware to
| mirror your original setup.
|
*/

Route::get('/', [ChartsController::class, 'index'])
    ->name('index');

Route::get('/complaints', [ChartsController::class, 'complaints'])
    ->name('complaints');

Route::get('/incidents', [ChartsController::class, 'incidents'])
    ->name('incidents');
