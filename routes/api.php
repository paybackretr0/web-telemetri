<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CalendarController;
use App\Http\Controllers\API\DelegationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\DutyScheduleController;
use App\Http\Controllers\API\HistoryController;

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
    Route::post('/update-device-token', [AuthController::class, 'updateDeviceToken']);
    
    // Absensi routes
    Route::prefix('absensi')->group(function () {
        Route::post('/{code}', [AttendanceController::class, 'scanQrCode']);
    });
        
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
        Route::apiResource('/', PermissionController::class)->except('show');
        Route::get('/{id}', [PermissionController::class, 'show']);
    });
        
    // Delegasi
    Route::prefix('delegations')->group(function () {
        Route::get('/my', [DelegationController::class, 'myDelegations']);
        Route::get('/', [DelegationController::class, 'index']);
        Route::post('/', [DelegationController::class, 'store']);
        Route::get('/{id}', [DelegationController::class, 'show']);
        Route::put('/{id}', [DelegationController::class, 'update']);
        Route::patch('/{id}', [DelegationController::class, 'update']);
        Route::delete('/{id}', [DelegationController::class, 'destroy']);
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

    Route::prefix('history')->group(function () {
        Route::get('/activities', [HistoryController::class, 'activityHistory']);
        Route::get('/meetings', [HistoryController::class, 'meetingHistory']);
        Route::get('/all', [HistoryController::class, 'allHistory']);
    });
});