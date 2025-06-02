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
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 overflow-hidden relative">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Gradient Orbs -->
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-gradient-to-r from-blue-400 to-purple-600 rounded-full mix-blend-screen filter blur-3xl opacity-70 animate-float"></div>
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full mix-blend-screen filter blur-3xl opacity-70 animate-float-delayed"></div>
        <div class="absolute -bottom-40 left-1/2 transform -translate-x-1/2 w-80 h-80 bg-gradient-to-r from-purple-400 to-pink-600 rounded-full mix-blend-screen filter blur-3xl opacity-70 animate-float-slow"></div>
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.1) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <!-- Floating Particles -->
        <div class="absolute inset-0">
            <div class="particle absolute w-2 h-2 bg-blue-400 rounded-full opacity-60 animate-particle-1"></div>
            <div class="particle absolute w-1 h-1 bg-purple-400 rounded-full opacity-80 animate-particle-2"></div>
            <div class="particle absolute w-1.5 h-1.5 bg-cyan-400 rounded-full opacity-70 animate-particle-3"></div>
            <div class="particle absolute w-1 h-1 bg-pink-400 rounded-full opacity-60 animate-particle-4"></div>
            <div class="particle absolute w-2 h-2 bg-indigo-400 rounded-full opacity-50 animate-particle-5"></div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                
                <!-- Left Side - Hero Section -->
                <div class="hidden lg:block space-y-8 animate-slide-in-left">
                    <!-- Logo and Brand -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4 group">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative bg-white p-3 rounded-2xl shadow-xl">
                                    <img src="/assets/images/NEO.png" alt="NEO Logo" class="h-12 w-auto">
                                </div>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                                    Neo Telemetri
                                </h1>
                                <p class="text-blue-300 font-medium">Sistem Manajemen Modern</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <h2 class="text-3xl font-bold text-white leading-tight">
                                Kelola <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Absensi</span> dengan Mudah
                            </h2>
                            <p class="text-gray-300 text-lg leading-relaxed">
                                Platform digital terdepan untuk mengelola absensi kegiatan, rapat, dan jadwal piket organisasi Anda dengan efisiensi maksimal.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Otomatisasi Penuh</h3>
                                <p class="text-gray-400 text-sm">Sistem absensi otomatis dengan integrasi real-time</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Performa Tinggi</h3>
                                <p class="text-gray-400 text-sm">Response cepat dengan teknologi modern</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6H4v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Keamanan Terjamin</h3>
                                <p class="text-gray-400 text-sm">Enkripsi end-to-end untuk data organisasi</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-xl"></div>
                        <div class="relative bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                        AD
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <blockquote class="text-white/90 italic">
                                        "Neo Telemetri mengubah cara kami mengelola organisasi. Produktivitas meningkat 300% dalam 3 bulan pertama."
                                    </blockquote>
                                    <div class="mt-3 flex items-center">
                                        <div>
                                            <p class="text-white font-semibold text-sm">Admin Demo</p>
                                            <p class="text-blue-300 text-xs">Chief Administrator</p>
                                        </div>
                                        <div class="ml-auto flex text-yellow-400">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="animate-slide-in-right">
                    <!-- Mobile Header -->
                    <div class="lg:hidden text-center mb-8">
                        <div class="flex items-center justify-center space-x-3 mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl blur opacity-75"></div>
                                <div class="relative bg-white p-2 rounded-xl">
                                    <img src="/assets/images/NEO.png" alt="NEO Logo" class="h-8 w-auto">
                                </div>
                            </div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                                Neo Telemetri
                            </h1>
                        </div>
                        <p class="text-gray-300">Masuk untuk melanjutkan</p>
                    </div>
                    
                    <!-- Login Card -->
                    <div class="relative">
                        <!-- Card Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-white/5 rounded-3xl blur-xl"></div>
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8 shadow-2xl">
                            
                            <!-- Desktop Header -->
                            <div class="hidden lg:block mb-8">
                                <h2 class="text-3xl font-bold text-white mb-2">Selamat Datang</h2>
                                <p class="text-gray-300">Masuk ke akun Anda untuk melanjutkan</p>
                            </div>
                            
                            <!-- Error Message -->
                            @if(session('error'))
                            <div class="mb-6 p-4 bg-red-500/20 backdrop-blur-sm border border-red-500/30 text-red-200 rounded-2xl animate-shake">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Google Login Section -->
                            <div class="space-y-6">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-lg"></div>
                                    <div class="relative bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                        <div class="text-center mb-6">
                                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mb-4 animate-pulse-slow">
                                                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24">
                                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="currentColor"/>
                                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="currentColor"/>
                                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="currentColor"/>
                                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="currentColor"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-white mb-2">Masuk dengan Google</h3>
                                            <p class="text-gray-300 text-sm">Gunakan akun Google untuk akses aman dan cepat</p>
                                        </div>
                                        
                                        <a href="{{ url('/auth/google') }}" 
                                           class="group relative w-full flex items-center justify-center px-6 py-4 bg-white hover:bg-gray-50 rounded-xl font-semibold text-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 overflow-hidden">
                                            <!-- Button Background Animation -->
                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                                            
                                            <!-- Google Icon -->
                                            <svg class="w-6 h-6 mr-3 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                            </svg>
                                            
                                            <span class="relative z-10">Lanjutkan dengan Google</span>
                                            
                                            <!-- Shimmer Effect -->
                                            <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Info Notice -->
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-amber-600/20 to-orange-600/20 rounded-2xl blur-lg"></div>
                                    <div class="relative bg-amber-500/10 backdrop-blur-md rounded-2xl p-4 border border-amber-500/20">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="w-6 h-6 text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-amber-200 text-sm font-medium">Akses Terbatas</p>
                                                <p class="text-amber-300/80 text-xs mt-1">Hanya pengguna terdaftar yang dapat mengakses sistem ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Back to Home -->
                            <div class="mt-8 text-center">
                                <a href="{{ url('/') }}" 
                                   class="group inline-flex items-center text-sm text-blue-300 hover:text-white transition-all duration-300">
                                    <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span class="relative">
                                        Kembali ke Beranda
                                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-20">
        <div class="bg-black/20 backdrop-blur-md rounded-full px-4 py-2 border border-white/10">
            <p class="text-white/60 text-xs">
                &copy; {{ date('Y') }} NEO Telemetri. All rights reserved.
            </p>
        </div>
    </div>
    
    <style>
        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(-5deg); }
        }
        
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(3deg); }
        }
        
        @keyframes slide-in-left {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        /* Particle Animations */
        @keyframes particle-float-4 {
            0% { transform: translate(30vw, 100vh) scale(0); }
            10% { transform: translate(30vw, 95vh) scale(1); }
            90% { transform: translate(60vw, 5vh) scale(1); }
            100% { transform: translate(60vw, 0vh) scale(0); }
        }
        
        @keyframes particle-float-5 {
            0% { transform: translate(70vw, 100vh) scale(0); }
            10% { transform: translate(70vw, 88vh) scale(1); }
            90% { transform: translate(40vw, 12vh) scale(1); }
            100% { transform: translate(40vw, 0vh) scale(0); }
        }
        
        /* Animation Classes */
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-float-delayed {
            animation: float-delayed 8s ease-in-out infinite;
            animation-delay: 2s;
        }
        
        .animate-float-slow {
            animation: float-slow 10s ease-in-out infinite;
            animation-delay: 4s;
        }
        
        .animate-slide-in-left {
            animation: slide-in-left 1s ease-out;
        }
        
        .animate-slide-in-right {
            animation: slide-in-right 1s ease-out;
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }
        
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
        
        .animate-particle-1 {
            animation: particle-float-1 15s linear infinite;
            animation-delay: 0s;
        }
        
        .animate-particle-2 {
            animation: particle-float-2 18s linear infinite;
            animation-delay: 3s;
        }
        
        .animate-particle-3 {
            animation: particle-float-3 20s linear infinite;
            animation-delay: 6s;
        }
        
        .animate-particle-4 {
            animation: particle-float-4 16s linear infinite;
            animation-delay: 9s;
        }
        
        .animate-particle-5 {
            animation: particle-float-5 22s linear infinite;
            animation-delay: 12s;
        }
        
        /* Particle positioning */
        .particle:nth-child(1) { left: 10%; top: 100%; }
        .particle:nth-child(2) { left: 80%; top: 100%; }
        .particle:nth-child(3) { left: 50%; top: 100%; }
        .particle:nth-child(4) { left: 30%; top: 100%; }
        .particle:nth-child(5) { left: 70%; top: 100%; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Glass morphism helper */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Gradient text helper */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .animate-slide-in-left,
            .animate-slide-in-right {
                animation: none;
            }
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .particle {
                display: none;
            }
            
            .animate-float,
            .animate-float-delayed,
            .animate-float-slow {
                animation-duration: 4s;
            }
        }
        
        /* Reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .particle {
                display: none;
            }
        }
        
        /* Dark mode enhancements */
        @media (prefers-color-scheme: dark) {
            .glass {
                background: rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        }
    </style>
</body>
</html>