<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('admin.dashboard');
});

Route::prefix('employees')->name('employees.')->controller(EmployeeController::class)->group(function () {
    Route::get('index', 'index')->name('index');
    Route::get('export/excel', 'exportExcel')->name('export.excel');
    Route::get('export/pdf', 'exportPdf')->name('export.pdf');
    Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store');
    Route::get('show/{id}', 'show')->name('show');
    Route::get('download-pdf/{id}', 'downloadPdf')->name('download-pdf');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::put('update/{id}', 'update')->name('update');
    Route::delete('destroy/{id}', 'destroy')->name('destroy');
});


Route::prefix('role-permission')->name('role-permission.')->controller(RoleController::class)->group(function () {
    Route::get('index', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store');
    Route::get('show/{id}', 'show')->name('show');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::put('update/{id}', 'update')->name('update');
    Route::delete('destroy/{id}', 'destroy')->name('destroy');
});

Route::prefix('user')->name('users.')->controller(UserController::class)->group(function () {
    Route::get('index', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store');
    Route::get('show/{id}', 'show')->name('show');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::put('update/{id}', 'update')->name('update');
    Route::delete('destroy/{id}', 'destroy')->name('destroy');
    Route::patch('toggle-status/{id}', 'toggleStatus')->name('toggle-status');
    Route::get('check-username', 'checkUsername')->name('check-username');
    Route::get('employee-suggest', 'employeeSuggest')->name('employee-suggest');
});

use App\Http\Controllers\Backend\TransportAllowanceController;

Route::prefix('transport-allowances')->name('transport-allowances.')->controller(TransportAllowanceController::class)->group(function () {
    Route::get('index', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store');
    Route::delete('destroy/{id}', 'destroy')->name('destroy');
});

use App\Http\Controllers\Backend\TransportSettingsController;

Route::prefix('transport-settings')->name('transport-settings.')->controller(TransportSettingsController::class)->group(function () {
    Route::get('index', 'index')->name('index');
    Route::post('update', 'update')->name('update');
});
