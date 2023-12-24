<?php

use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

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

Route::controller(UserController::class)
    ->prefix('v1/users')
    ->name('users.')
    ->group(function () {
        Route::get('/scan-card/{user}', 'scanCard')->name('scan-card');
        Route::get('/let-car/{user}', 'letCar')->name('let-car');
        Route::get('/release-car/{user}', 'releaseCar')->name('release-car');
    });
