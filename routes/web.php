<?php

use Illuminate\Support\Facades\Route;

// Frontend routes
require __DIR__ . '/auth/frontend.php';

// Backend routes (jika pakai prefix /admin)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    require __DIR__ . '/auth/backend.php';
});

Route::get('/', function () {
    return view('frontend.pages.home'); // atau landing page
})->name('home');
