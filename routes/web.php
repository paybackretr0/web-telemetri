<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DelegationController;
use App\Http\Controllers\Admin\DutyController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\QrDutyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Guest routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('pengguna', PenggunaController::class);

    Route::prefix('permission')->name('admin.permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [PermissionController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PermissionController::class, 'reject'])->name('reject');
    });

    // Delegation routes
    Route::prefix('delegation')->name('admin.delegation.')->group(function () {
        Route::get('/', [DelegationController::class, 'index'])->name('index');
        Route::post('/', [DelegationController::class, 'store'])->name('store');
        Route::get('/{id}', [DelegationController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DelegationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DelegationController::class, 'update'])->name('update');
        Route::post('/{id}/approve', [DelegationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [DelegationController::class, 'reject'])->name('reject');
        Route::post('/{id}/cancel', [DelegationController::class, 'cancel'])->name('cancel');
    });

    Route::resource('duty', DutyController::class);

    Route::prefix('attendance')->name('admin.attendance.')->group(function () {
        Route::get('/{id}/{type}/edit', [AttendanceController::class, 'edit'])->name('edit.type');
        Route::put('/{id}/{type}', [AttendanceController::class, 'update'])->name('update.type');
        Route::delete('/{id}/{type}', [AttendanceController::class, 'destroy'])->name('destroy.type');
        Route::get('/{id}/qrcode', [AttendanceController::class, 'showQrCode'])->name('qrcode');
        Route::post('/{id}/regenerate-qr', [AttendanceController::class, 'regenerateQrCode'])->name('regenerate-qr');
    });
    Route::resource('attendance', AttendanceController::class)->except(['edit', 'update', 'destroy']);

    // QR Duty routes
    Route::prefix('qrduty')->name('admin.qrduty.')->group(function () {
        Route::get('/', [QrDutyController::class, 'index'])->name('index');
        Route::post('/', [QrDutyController::class, 'store'])->name('store');
        Route::put('/{id}', [QrDutyController::class, 'update'])->name('update');
        Route::delete('/{id}', [QrDutyController::class, 'destroy'])->name('destroy');
        Route::get('/show/{code}', [QrDutyController::class, 'showQrCode'])->name('show');
    });
});