<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\TransportAllowanceController;
use App\Http\Controllers\Backend\TransportSettingsController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('backend.home.index');
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('employees/index', function () {
    return redirect()->route('employees.index');
})->name('employees.legacy-index');

Route::resource('employees', EmployeeController::class);

Route::prefix('employees')->name('employees.')->controller(EmployeeController::class)->group(function () {
    Route::get('export/excel', 'exportExcel')->name('export.excel');
    Route::get('export/pdf', 'exportPdf')->name('export.pdf');
    Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    Route::get('download-pdf/{id}', 'downloadPdf')->name('download-pdf');
});

Route::resource('role-permission', RoleController::class);

Route::prefix('user')->name('users.')->controller(UserController::class)->group(function () {
    Route::delete('destroy/{id}', 'destroy')->name('destroy');
    Route::patch('toggle-status/{id}', 'toggleStatus')->name('toggle-status');
    Route::get('check-username', 'checkUsername')->name('check-username');
    Route::get('show/{id}', 'show')->name('show');
    Route::get('employee-suggest', 'employeeSuggest')->name('employee-suggest');
});

Route::prefix('user')->name('backend.user.')->controller(UserController::class)->group(function () {
    Route::get('show/{user?}', 'show')->name('show');
    Route::get('change-password', 'editPassword')->name('changePassword');
    Route::put('change-password', 'updatePassword')->name('updatePassword');
});

Route::resource('users', UserController::class);

Route::resource('transport-allowances', TransportAllowanceController::class);

Route::resource('transport-settings', TransportSettingsController::class);
