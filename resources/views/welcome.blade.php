<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home - Telemetri</title>
        <link rel="icon" href="/assets/images/NEO.png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=inter:400,500,600,700" rel="stylesheet" />
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md mx-auto bg-white rounded-xl shadow-lg p-8 mt-12">
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('assets/images/NEO.png') }}" alt="NEO Logo" class="h-16 mb-3">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Neo Telemetri</h1>
                <p class="text-gray-500 text-sm mb-4">Sistem Manajemen Organisasi Modern</p>
            </div>
            <div class="mb-8 text-center">
                <p class="text-gray-700 mb-4">
                    Selamat datang di <span class="font-semibold text-blue-600">Neo Telemetri</span>.<br>
                    Solusi digital untuk absensi, rapat, dan penjadwalan organisasi Anda.
                </p>
                <a href="{{ route('login') }}"
                class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition">
                    Masuk ke Dashboard
                </a>
            </div>
            <div class="text-center text-gray-400 text-xs mt-8">
                &copy; {{ date('Y') }} NEO Telemetri. All rights reserved.
            </div>
        </div>
    </body>
</html>