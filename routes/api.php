<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RapatController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CalendarController;
use App\Http\Controllers\API\DelegationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\DutyScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/google-flutter', [AuthController::class, 'googleLogin']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
Route::post('/refresh-google-token', [AuthController::class, 'refreshGoogleToken'])->middleware('auth:sanctum');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    // Route::get('/user', [UserController::class, 'show']);
    // Route::put('/user', [UserController::class, 'update']);
    
    // // Absensi routes
    // Route::prefix('absensi')->group(function () {
    //     // Absensi Piket
    //     Route::get('/piket', [AbsensiController::class, 'getPiketList']);
    //     Route::post('/piket/scan', [AbsensiController::class, 'scanPiket']);
    //     Route::get('/piket/history', [AbsensiController::class, 'getPiketHistory']);
        
    //     // Absensi Rapat & Kegiatan
    //     Route::get('/kegiatan', [AbsensiController::class, 'getKegiatanList']);
    //     Route::post('/kegiatan/scan', [AbsensiController::class, 'scanKegiatan']);
    //     Route::get('/kegiatan/history', [AbsensiController::class, 'getKegiatanHistory']);
        
    // Aktivitas
    Route::prefix('activities')->group(function () {
        Route::get('/upcoming', [ActivityController::class, 'getUpcomingActivities']);
        Route::get('/attendance/{id}', [ActivityController::class, 'getActivityForAttendance']);
        Route::get('/types', [ActivityController::class, 'getAttendanceTypes']);
        Route::apiResource('/', ActivityController::class);
    });
    
    // Izin
    Route::prefix('permissions')->group(function () {
        Route::get('/my', [PermissionController::class, 'myPermissions']);
        Route::apiResource('/', PermissionController::class);
    });
        
    // Delegasi
    Route::prefix('delegations')->group(function () {
        Route::get('/my', [DelegationController::class, 'myDelegations']);
        Route::apiResource('/', DelegationController::class);
        Route::patch('/process/{id}', [DelegationController::class, 'processDelegations']);
    });
    
    // Jadwal Piket
    Route::prefix('duty-schedules')->group(function () {
        Route::get('/my', [DutyScheduleController::class, 'myDutySchedules']);
        Route::get('/next', [DutyScheduleController::class, 'getNextDuty']);
        Route::get('/delegable', [DutyScheduleController::class, 'getDelegableDutySchedules']);
        Route::get('/potential-delegates', [DutyScheduleController::class, 'getPotentialDelegates']);
    });
    
    // Notifikasi
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount']);
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::delete('/', [NotificationController::class, 'destroyAll']);
    });

    // Profil
    Route::prefix('profile')->group(function () {
        Route::get('/my', [ProfileController::class, 'getProfile']);
        Route::post('/update', [ProfileController::class, 'updateProfile']);
    });

    // Kalender
    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'getEvents']);
    });
});

// Admin routes
// Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
//     // User management
//     Route::get('/users', [UserController::class, 'index']);
//     Route::get('/users/{id}', [UserController::class, 'showUser']);
//     Route::post('/users', [UserController::class, 'store']);
//     Route::put('/users/{id}', [UserController::class, 'updateUser']);
//     Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
//     // QR code generation
//     Route::post('/qrcode/generate', [AbsensiController::class, 'generateQRCode']);
//     Route::get('/qrcode/{id}', [AbsensiController::class, 'getQRCode']);
    
//     // Absensi management
//     Route::get('/absensi/report', [AbsensiController::class, 'generateReport']);
//     Route::put('/absensi/{id}/status', [AbsensiController::class, 'updateStatus']);
    
//     // Notifications
//     Route::post('/notifications', [NotificationController::class, 'send']);
// });