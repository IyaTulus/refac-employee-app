<?php

use App\Http\Controllers\Backend\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('menus', [MenuController::class, 'index']);
// Route::middleware('auth:sanctum')->group(function () {
// });
