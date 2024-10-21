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

Route::middleware('auth')->group(function () {
    Route::any('/', [HomeController::class, 'home']);
    Route::any('home', [HomeController::class, 'home'])->name('home');
    Route::any('impostazioni', [HomeController::class, 'impostazioni'])->name('impostazioni');
    Route::post('/settingsSave', [HomeController::class, 'settingsSave'])->name('settingsSave');

});

Route::any('clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');

    return 'Cache is cleared';
});
