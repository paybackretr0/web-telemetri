<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Periksa apakah pengguna memiliki role 'admin'
                if ($existingUser->role !== 'admin') {
                    return redirect('/login')->with('error', 'Hanya admin yang diizinkan untuk login ke sistem ini.');
                }
                
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]);
                Auth::login($existingUser);
                
                return redirect()->route('admin.dashboard');
            } else {
                // User not found in database, redirect back with error
                return redirect('/login')->with('error', 'Email Anda tidak terdaftar dalam sistem kami. Silakan hubungi administrator.');
            }
        
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }
}