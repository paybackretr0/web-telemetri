<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\ProkerController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RapatController;
use App\Http\Controllers\API\KalenderController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GoogleController;

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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
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
        
    //     // Izin
    //     Route::post('/izin', [AbsensiController::class, 'createIzin']);
    //     Route::get('/izin', [AbsensiController::class, 'getIzinList']);
    //     Route::get('/izin/{id}', [AbsensiController::class, 'getIzinDetail']);
    //     Route::put('/izin/{id}', [AbsensiController::class, 'updateIzin']);
    //     Route::delete('/izin/{id}', [AbsensiController::class, 'deleteIzin']);
        
    //     // Delegasi
    //     Route::post('/delegasi', [AbsensiController::class, 'createDelegasi']);
    //     Route::get('/delegasi', [AbsensiController::class, 'getDelegasiList']);
    //     Route::get('/delegasi/{id}', [AbsensiController::class, 'getDelegasiDetail']);
    //     Route::put('/delegasi/{id}', [AbsensiController::class, 'updateDelegasi']);
    //     Route::delete('/delegasi/{id}', [AbsensiController::class, 'deleteDelegasi']);
    // });
    
    // // Proker (Program Kerja) routes
    // Route::prefix('proker')->group(function () {
    //     Route::get('/', [ProkerController::class, 'index']);
    //     Route::get('/{id}', [ProkerController::class, 'show']);
    //     Route::post('/', [ProkerController::class, 'store'])->middleware('role:admin');
    //     Route::put('/{id}', [ProkerController::class, 'update'])->middleware('role:admin');
    //     Route::delete('/{id}', [ProkerController::class, 'destroy'])->middleware('role:admin');
    //     Route::get('/{id}/activities', [ProkerController::class, 'getActivities']);
    // });
    
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