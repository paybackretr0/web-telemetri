<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Telemetri</title>
        <link rel="icon" href="/assets/images/NEO.png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=inter:400,500,600,700" rel="stylesheet" />
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
            <div class="flex flex-col items-center mb-8">
                <img src="/assets/images/NEO.png" alt="NEO Logo" class="h-16 mb-3">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Neo Telemetri</h1>
                <p class="text-gray-500 text-sm">Sistem Manajemen Modern</p>
            </div>
            @if(session('error'))
                <div class="mb-6 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
            <div class="mb-6">
                <a href="{{ url('/auth/google') }}"
                class="w-full flex items-center justify-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="font-medium text-gray-700">Login dengan Google</span>
                </a>
            </div>
            <div class="text-center text-gray-400 text-xs mt-8">
                &copy; {{ date('Y') }} NEO Telemetri. All rights reserved.
            </div>
        </div>
    </body>
</html>