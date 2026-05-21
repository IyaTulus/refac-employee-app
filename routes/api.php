<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    foreach (glob(__DIR__ . '/Api/*.php') as $routeFile) {
        require $routeFile;
    }
});
