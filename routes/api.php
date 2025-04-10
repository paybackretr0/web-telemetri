<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\ProkerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RapatController;
use App\Http\Controllers\API\KalenderController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\AuthController;
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

Route::get('test', function() {
    return response()->json(['message' => 'API is working!']);
});

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
    Route::get('activities/upcoming', [ActivityController::class, 'getUpcomingActivities']);
    Route::get('activities/attendance/{id}', [ActivityController::class, 'getActivityForAttendance']);
    Route::get('activity-types', [ActivityController::class, 'getAttendanceTypes']);
    Route::apiResource('activities', ActivityController::class);
    
    // Izin
    Route::get('permissions/my', [PermissionController::class, 'myPermissions']);
    Route::apiResource('permissions', PermissionController::class);
        
    // Delegasi
    Route::get('delegations/my', [DelegationController::class, 'myDelegations']);
    Route::apiResource('delegations', DelegationController::class);
    Route::patch('delegations/process/{id}', [DelegationController::class, 'processDelegations']);
    
    // Duty Schedules
    Route::get('duty-schedules/my', [DutyScheduleController::class, 'myDutySchedules']);
    Route::get('duty-schedules/next', [DutyScheduleController::class, 'getNextDuty']);
    Route::get('duty-schedules/delegable', [DutyScheduleController::class, 'getDelegableDutySchedules']);
    Route::get('duty-schedules/potential-delegates', [DutyScheduleController::class, 'getPotentialDelegates']);
    
    // // Rapat routes
    // Route::prefix('rapat')->group(function () {
    //     Route::get('/', [RapatController::class, 'index']);
    //     Route::get('/{id}', [RapatController::class, 'show']);
    //     Route::post('/', [RapatController::class, 'store'])->middleware('role:admin');
    //     Route::put('/{id}', [RapatController::class, 'update'])->middleware('role:admin');
    //     Route::delete('/{id}', [RapatController::class, 'destroy'])->middleware('role:admin');
    // });
    
    // // Kalender routes
    // Route::prefix('kalender')->group(function () {
    //     Route::get('/', [KalenderController::class, 'getEvents']);
    //     Route::post('/sync', [KalenderController::class, 'syncWithGoogle']);
    //     Route::post('/add-to-google', [KalenderController::class, 'addToGoogleCalendar']);
    // });
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