<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::any('login', [AuthController::class, 'login'])->name('login');
Route::any('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('signin', [AuthController::class, 'signin'])->name('signin');

if (env('APP_NAME') !== 'RP1') {
    Route::middleware('auth')->group(function () {
        Route::any('/', [HomeController::class, 'home']);
        Route::any('home', [HomeController::class, 'home'])->name('home');
        Route::post('settingsSave', [HomeController::class, 'settingsSave'])->name('settingsSave');
    });
} else {
    Route::any('/', [HomeController::class, 'home']);
    Route::any('home', [HomeController::class, 'home'])->name('home');
    Route::post('settingsSave', [HomeController::class, 'settingsSave'])->name('settingsSave');
    Route::any('getInfoCartellino', [HomeController::class, 'getInfoCartellino'])->name('getInfoCartellino');
}

Route::middleware('auth')->group(function () {
    Route::any('reports', [HomeController::class, 'reports'])->name('reports');
    Route::any('parametri', [HomeController::class, 'parametri'])->name('parametri');
    Route::any('impostazioni', [HomeController::class, 'impostazioni'])->name('impostazioni');
    Route::any('rete', [HomeController::class, 'rete'])->name('rete');
    Route::any('manuale', [HomeController::class, 'manuale'])->name('manuale');
    Route::post('impostaRete', [HomeController::class, 'impostaRete'])->name('impostaRete');
    Route::post('settingsSaveAll', [HomeController::class, 'settingsSaveAll'])->name('settingsSaveAll');
    Route::post('aggiornaPin', [HomeController::class, 'aggiornaPin'])->name('aggiornaPin');
    Route::any('reboot', [HomeController::class, 'reboot'])->name('reboot');
    Route::any('shutdown', [HomeController::class, 'shutdown'])->name('shutdown');
    Route::any('campionatura', [HomeController::class, 'campionatura'])->name('campionatura');
    Route::any('signalCampionatura', [HomeController::class, 'signalCampionatura'])->name('signalCampionatura');
    Route::any('getSettings', [HomeController::class, 'getSettings'])->name('getSettings');
});

Route::any('clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');

    return 'Cache is cleared';
});
