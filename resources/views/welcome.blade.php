<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/images/NEO.png') }}">
    <title>Telemetri - Sistem Manajemen Organisasi Modern</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=space-grotesk:400,500,600,700" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.6); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-slideInLeft {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        
        .animate-slideInRight {
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        .animate-pulse-custom {
            animation: pulse 2s ease-in-out infinite;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
        }
        
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .ripple:hover:before {
            width: 300px;
            height: 300px;
        }
        
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-blue-900 dark:to-indigo-900 min-h-screen font-instrument-sans antialiased overflow-x-hidden">
    
    <!-- Floating Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-10 animate-float" style="animation-delay: -1.5s;"></div>
    </div>

    <!-- Navbar -->
    <nav class="glass-effect fixed w-full z-50 transition-all duration-500 border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4 animate-slideInLeft">
                    <div class="relative">
                        <img src="{{ asset('assets/images/NEO.png') }}" alt="NEO Logo" class="h-12 w-auto object-contain animate-pulse-custom">
                        <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-md animate-glow"></div>
                    </div>
                    <div class="text-3xl font-bold gradient-text tracking-tight font-space-grotesk">
                        Telemetri
                    </div>
                </div>
                <div class="flex items-center space-x-8 animate-slideInRight">
                    <a href="{{ route('login') }}" class="relative text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300 hover:bg-white/20 dark:hover:bg-gray-800/50 backdrop-blur-sm group">
                        <span class="relative z-10">Login</span>
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </a>
                    <a href="#features" class="relative text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300 hover:bg-white/20 dark:hover:bg-gray-800/50 backdrop-blur-sm group">
                        <span class="relative z-10">Fitur</span>
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:items-center lg:justify-between lg:space-x-16">
                <div class="lg:w-1/2 mb-16 lg:mb-0">
                    <div class="space-y-8">
                        <div class="animate-fadeInUp opacity-0 delay-100">
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 dark:text-white leading-tight mb-8 text-shadow font-space-grotesk">
                                Manajemen 
                                <span class="gradient-text relative">
                                    Absensi
                                    <div class="absolute -bottom-2 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
                                </span> 
                                Modern
                            </h1>
                        </div>
                        
                        <div class="animate-fadeInUp opacity-0 delay-200">
                            <p class="text-xl text-gray-600 dark:text-gray-300 mb-12 max-w-2xl leading-relaxed">
                                <span class="font-semibold text-blue-600 dark:text-blue-400">Neo Telemetri</span>: 
                                Solusi digital canggih untuk absensi, rapat, dan penjadwalan piket organisasi dengan 
                                <span class="font-semibold">efisiensi maksimal</span> dan teknologi terdepan.
                            </p>
                        </div>
                        
                        <div class="animate-fadeInUp opacity-0 delay-300">
                            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6">
                                <a href="{{ route('login') }}" class="group relative inline-flex justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg rounded-2xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-2xl transition-all duration-300 ripple transform hover:scale-105">
                                    <span class="relative z-10 flex items-center space-x-2">
                                        <span>Masuk ke Dashboard</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </span>
                                </a>
                                <a href="#features" class="group relative inline-flex justify-center px-8 py-4 glass-effect text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 rounded-2xl font-bold text-lg focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-xl transition-all duration-300 transform hover:scale-105">
                                    <span class="flex items-center space-x-2">
                                        <span>Pelajari Fitur</span>
                                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="animate-fadeInUp opacity-0 delay-400">
                            <div class="grid grid-cols-3 gap-8 pt-12 border-t border-gray-200/50 dark:border-gray-700/50">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">99.9%</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Uptime</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">24/7</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Support</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">1000+</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Users</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 flex justify-center animate-slideInRight opacity-0 delay-500">
                    <div class="relative w-full max-w-2xl">
                        <!-- Animated Background Blobs -->
                        <div class="absolute top-0 -left-20 w-80 h-80 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-float"></div>
                        <div class="absolute top-0 -right-20 w-80 h-80 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-float" style="animation-delay: -2s;"></div>
                        <div class="absolute -bottom-20 left-20 w-80 h-80 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-float" style="animation-delay: -4s;"></div>
                        
                        <!-- Main Image Container -->
                        <div class="relative z-10 group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur-2xl opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
                            <div class="relative glass-effect rounded-3xl p-8 shadow-2xl transform group-hover:scale-105 transition-all duration-500">
                                <img class="rounded-2xl shadow-2xl w-full h-96 object-cover transform group-hover:scale-110 transition-transform duration-700" 
                                     src="{{ asset('assets/images/dashboard-preview.jpg') }}" 
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'" 
                                     alt="Dashboard Preview">
                                
                                <!-- Floating UI Elements -->
                                <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl flex items-center justify-center shadow-2xl animate-float" style="animation-delay: -1s;">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                
                                <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl flex items-center justify-center shadow-2xl animate-float" style="animation-delay: -3s;">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <div class="animate-fadeInUp opacity-0">
                    <span class="inline-block px-6 py-2 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 text-blue-600 dark:text-blue-400 rounded-full text-sm font-semibold mb-6">
                        Fitur Unggulan
                    </span>
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 font-space-grotesk">
                        Teknologi <span class="gradient-text">Terdepan</span>
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                        Transformasi manajemen organisasi Anda dengan solusi inovatif yang dirancang untuk efisiensi maksimal
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 mb-20">
                <!-- Feature 1: Presensi -->
                <div class="feature-card glass-effect rounded-3xl p-8 shadow-2xl hover:shadow-3xl group animate-fadeInUp opacity-0 delay-100">
                    <div class="relative mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:shadow-2xl transition-all duration-300 animate-glow">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">Manajemen Presensi</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Pantau kehadiran secara real-time dengan teknologi QR Code untuk kegiatan dan rapat yang terorganisir dengan sempurna.</p>
                </div>

                <!-- Feature 2: Jadwal Piket -->
                <div class="feature-card glass-effect rounded-3xl p-8 shadow-2xl hover:shadow-3xl group animate-fadeInUp opacity-0 delay-200">
                    <div class="relative mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:shadow-2xl transition-all duration-300 animate-glow" style="animation-delay: -1s;">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-purple-400 rounded-full animate-pulse" style="animation-delay: -0.5s;"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">Jadwal Piket</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Atur dan koordinasikan piket anggota dengan fleksibilitas tinggi dan kemudahan pergantian jadwal yang intuitif.</p>
                </div>

                <!-- Feature 3: Manajemen Perizinan -->
                <div class="feature-card glass-effect rounded-3xl p-8 shadow-2xl hover:shadow-3xl group animate-fadeInUp opacity-0 delay-300">
                    <div class="relative mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:shadow-2xl transition-all duration-300 animate-glow" style="animation-delay: -2s;">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-teal-400 rounded-full animate-pulse" style="animation-delay: -1.5s;"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300">Manajemen Perizinan</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Kontrol akses anggota dengan sistem perizinan yang aman, terpusat, dan mudah dikelola oleh administrator.</p>
                </div>
            </div>
            
            <!-- Additional Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-effect rounded-3xl p-8 shadow-xl animate-fadeInUp opacity-0 delay-400">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Analytics & Reporting</h4>
                            <p class="text-gray-600 dark:text-gray-300">Dapatkan insight mendalam dengan laporan komprehensif dan visualisasi data yang interaktif.</p>
                        </div>
                    </div>
                </div>
                
                <div class="glass-effect rounded-3xl p-8 shadow-xl animate-fadeInUp opacity-0 delay-500">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Keamanan Tingkat Tinggi</h4>
                            <p class="text-gray-600 dark:text-gray-300">Perlindungan data dengan enkripsi end-to-end dan sistem keamanan berlapis yang terpercaya.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-32 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800"></div>
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fadeInUp opacity-0">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-8 text-shadow font-space-grotesk">
                    Mulai Transformasi 
                    <span class="text-yellow-300">Sekarang</span>
                </h2>
                <p class="text-xl text-blue-100 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Bergabunglah dengan ribuan organisasi yang telah merasakan efisiensi luar biasa dengan 
                    <span class="font-bold text-white">Telemetri</span>. Akses dashboard canggih untuk mengelola absensi dan jadwal dengan mudah.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-6 sm:space-y-0 sm:space-x-8 mb-16">
                    <a href="{{ route('login') }}" class="group relative inline-flex justify-center px-10 py-5 bg-white text-blue-600 font-bold text-xl rounded-2xl hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/50 shadow-2xl transition-all duration-300 ripple transform hover:scale-110">
                        <span class="flex items-center space-x-3">
                            <span>Masuk Sekarang</span>
                            <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </a>
                    
                    <div class="flex items-center space-x-2 text-blue-100">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Gratis untuk 30 hari pertama</span>
                    </div>
                </div>
                
                <!-- Trust Indicators -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="text-blue-100">
                        <div class="text-3xl font-bold text-white mb-2">1000+</div>
                        <div class="text-sm">Organisasi Terdaftar</div>
                    </div>
                    <div class="text-blue-100">
                        <div class="text-3xl font-bold text-white mb-2">50K+</div>
                        <div class="text-sm">Pengguna Aktif</div>
                    </div>
                    <div class="text-blue-100">
                        <div class="text-3xl font-bold text-white mb-2">99.9%</div>
                        <div class="text-sm">Uptime Server</div>
                    </div>
                    <div class="text-blue-100">
                        <div class="text-3xl font-bold text-white mb-2">4.9★</div>
                        <div class="text-sm">Rating Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-32 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 animate-fadeInUp opacity-0">
                <span class="inline-block px-6 py-2 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 text-blue-600 dark:text-blue-400 rounded-full text-sm font-semibold mb-6">
                    Testimoni
                </span>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 font-space-grotesk">
                    Apa Kata <span class="gradient-text">Pengguna</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Ribuan organisasi telah merasakan manfaat luar biasa dari Telemetri
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="glass-effect rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 animate-fadeInUp opacity-0 delay-100">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            A
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Ahmad Ridwan</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ketua OSIS SMAN 1</p>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        "Telemetri benar-benar mengubah cara kami mengelola kehadiran anggota OSIS. Sekarang semua bisa dipantau real-time dan sangat mudah!"
                    </p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="glass-effect rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 animate-fadeInUp opacity-0 delay-200">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            S
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Sari Dewi</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sekretaris Himpunan</p>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        "Interface yang sangat user-friendly dan fitur laporan yang detail. Pekerjaan administrasi jadi jauh lebih efisien!"
                    </p>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="glass-effect rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 animate-fadeInUp opacity-0 delay-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            B
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Budi Santoso</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Koordinator Divisi</p>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        "Sistem QR Code untuk absensi sangat praktis. Tim kami bisa fokus pada kegiatan utama tanpa ribet urusan administrasi."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900 dark:from-gray-950 dark:via-blue-950 dark:to-indigo-950 py-20 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Logo & Description -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="relative">
                            <img src="{{ asset('assets/images/NEO.png') }}" alt="NEO Logo" class="h-12 w-auto">
                            <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-md animate-glow"></div>
                        </div>
                        <span class="text-3xl font-bold text-white font-space-grotesk">Telemetri</span>
                    </div>
                    <p class="text-gray-300 text-lg mb-8 max-w-md">
                        Solusi digital terdepan untuk manajemen organisasi modern dengan teknologi canggih dan interface yang intuitif.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all duration-300 hover-scale-110">
    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
    </svg>
</a>
</div>
</div>

<!-- Navigation Links -->
<div>
<h5 class="text-lg font-bold text-white mb-6">Navigasi</h5>
<ul class="space-y-4">
    <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-300">Login</a></li>
    <li><a href="#features" class="text-gray-300 hover:text-white transition-colors duration-300">Fitur</a></li>
    <li><a href="#contact" class="text-gray-300 hover:text-white transition-colors duration-300">Kontak</a></li>
</ul>
</div>

<!-- Support Links -->
<div>
<h5 class="text-lg font-bold text-white mb-6">Dukungan</h5>
<ul class="space-y-4">
    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">FAQ</a></li>
    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Bantuan</a></li>
    <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Kebijakan Privasi</a></li>
</ul>
</div>

<!-- Contact Info -->
<div>
<h5 class="text-lg font-bold text-white mb-6">Kontak Kami</h5>
<ul class="space-y-4 text-gray-300">
    <li class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
        </svg>
        <span>+62 123 456 7890</span>
    </li>
    <li class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
        </svg>
        <span>support@telemetri.id</span>
    </li>
    <li class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
        </svg>
        <span>Jakarta, Indonesia</span>
    </li>
</ul>
</div>
</div>

<!-- Footer Bottom -->
<div class="mt-12 pt-8 border-t border-gray-700/50 text-center">
<p class="text-gray-400 text-sm">&copy; 2025 Neo Telemetri. All rights reserved.</p>
</div>
</div>
</footer>

</body>
</html>