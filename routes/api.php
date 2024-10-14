<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('token')->group(function () {
    Route::any('getSetting/{setting}', [ApiController::class, 'getSetting'])->name('getSetting');
    Route::any('setSetting/{setting}', [ApiController::class, 'setSetting'])->name('setSetting');
    // Route::any('getLogs', [ApiController::class, 'getLogs'])->name('getLogs');
});
