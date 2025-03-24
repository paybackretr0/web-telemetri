<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// routes/web.php
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

Route::get('/', function () {
    return view('welcome');
});