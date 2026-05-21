<?php

use App\Http\Controllers\Frontend\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'create')->name('login');
        Route::post('login', 'store');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::post('logout', 'destroy')->name('logout');
    });
});
