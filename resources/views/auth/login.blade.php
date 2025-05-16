<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Telemetri</title>
    <link rel="icon" href="/assets/images/NEO.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-950 min-h-screen flex flex-col items-center justify-center p-6 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-300 dark:bg-blue-700 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-blue-400 dark:bg-blue-600 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-500 dark:bg-blue-500 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>
    
    <div class="w-full max-w-4xl relative z-10 flex flex-col md:flex-row shadow-2xl rounded-2xl overflow-hidden">
        <!-- Left Side - Illustration (Hidden on Mobile) -->
        <div class="hidden md:block md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-900 p-8 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)" />
                </svg>
            </div>
            
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-10">
                        <img src="/assets/images/NEO.png" alt="NEO Logo" class="h-10 w-auto">
                        <h1 class="text-3xl font-bold text-white">Neo Telemetri</h1>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-white mb-4">Sistem Informasi Manajemen Absensi</h2>
                    <p class="text-blue-100 mb-6">Kelola absensi kegiatan, rapat, dan jadwal piket organisasi Anda secara digital dengan Neo Telemetri.</p>
                </div>
                
                <div class="rounded-lg bg-white/10 backdrop-blur-sm p-4 border border-white/20">
                    <p class="text-white text-sm italic">"Telemetri membantu kami mengorganisir kegiatan dengan lebih efisien dan meningkatkan produktivitas tim."</p>
                    <div class="mt-2 flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-xs">AD</div>
                        <div class="ml-2">
                            <p class="text-white text-xs font-semibold">Admin Demo</p>
                            <p class="text-blue-200 text-xs">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative Elements -->
            <div class="absolute bottom-0 right-0 transform translate-y-1/4 translate-x-1/4">
                <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="60" cy="60" r="60" fill="white" fill-opacity="0.1"/>
                </svg>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 bg-white dark:bg-gray-800 p-8 md:p-12">
            <div class="text-center mb-8 md:hidden">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <img src="/assets/images/NEO.png" alt="NEO Logo" class="h-8 w-auto">
                    <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">Telemetri</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-300">Masuk ke akun Anda untuk melanjutkan</p>
            </div>
            
            <div class="hidden md:block mb-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang Kembali</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
            </div>
            
            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-500 text-red-700 dark:text-red-400 rounded-md animate-pulse">
                {{ session('error') }}
            </div>
            @endif
            
            <div class="space-y-6">
                <!-- Login with Google -->
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Masuk dengan Google</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Gunakan akun Google Anda untuk masuk ke sistem Telemetri</p>
                    
                    <a href="{{ url('/auth/google') }}" 
                       class="flex items-center justify-center gap-3 w-full py-3 px-4 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-300 shadow-sm hover:shadow group">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-300 group-hover:scale-110">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-base font-medium text-gray-700 dark:text-gray-200">Lanjutkan dengan Google</span>
                    </a>
                </div>
                
                <div class="text-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <p>Hanya pengguna dengan akun terdaftar yang dapat masuk ke sistem ini.</p>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    
    <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 relative z-10">
        &copy; {{ date('Y') }} NEO Telemetri. Hak Cipta Dilindungi Undang-Undang.
    </div>
    
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>