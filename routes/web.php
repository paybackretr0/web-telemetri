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

    Route::get('/permission', [PermissionController::class, 'index'])->name('admin.permissions');
    Route::get('/permission/{id}', [PermissionController::class, 'show'])->name('admin.permissions.show');
    Route::post('/permission/{id}/approve', [PermissionController::class, 'approve'])->name('admin.permissions.approve');
    Route::post('/permission/{id}/reject', [PermissionController::class, 'reject'])->name('admin.permissions.reject');

    // Delegation routes
    Route::get('/delegation', [DelegationController::class, 'index'])->name('admin.delegations');
    Route::post('/delegation', [DelegationController::class, 'store'])->name('admin.delegations.store');
    Route::get('/delegation/{id}', [DelegationController::class, 'show'])->name('admin.delegations.show');
    Route::get('/delegation/{id}/edit', [DelegationController::class, 'edit'])->name('admin.delegations.edit');
    Route::put('/delegation/{id}', [DelegationController::class, 'update'])->name('admin.delegations.update');
    Route::post('/delegation/{id}/approve', [DelegationController::class, 'approve'])->name('admin.delegations.approve');
    Route::post('/delegation/{id}/reject', [DelegationController::class, 'reject'])->name('admin.delegations.reject');
    Route::post('/delegation/{id}/cancel', [DelegationController::class, 'cancel'])->name('admin.delegations.cancel');

    Route::resource('duty', DutyController::class);

    Route::get('/attendance/{id}/{type}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit.type');
    Route::put('/attendance/{id}/{type}', [AttendanceController::class, 'update'])->name('attendance.update.type');
    Route::delete('/attendance/{id}/{type}', [AttendanceController::class, 'destroy'])->name('attendance.destroy.type');
    Route::get('/attendance/{id}/qrcode', [AttendanceController::class, 'showQrCode'])->name('attendance.qrcode');
    Route::post('/attendance/{id}/regenerate-qr', [AttendanceController::class, 'regenerateQrCode'])->name('attendance.regenerate-qr');
    Route::get('/attendance/{id}/qrcode-download', [AttendanceController::class, 'downloadQrCode'])->name('attendance.qrcode-download');
    Route::resource('attendance', AttendanceController::class)->except(['edit', 'update', 'destroy']);

    Route::get('/qrduty', [QrDutyController::class, 'index'])->name('admin.qrduty');
});