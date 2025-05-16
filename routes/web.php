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

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('admin.pengguna');

    Route::get('/permission', [PermissionController::class, 'index'])->name('admin.permissions');

    Route::get('/delegation', [DelegationController::class, 'index'])->name('admin.delegations');

    Route::get('/duty', [DutyController::class, 'index'])->name('admin.duty');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');
});