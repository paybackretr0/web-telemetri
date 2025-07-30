<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/images/NEO.png') }}">
    <title>Telemetri - Sistem Manajemen Organisasi Modern</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=space-grotesk:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* CSS yang lebih simpel, hanya menyisakan yang penting */
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
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-gray-900 min-h-screen font-instrument-sans antialiased overflow-x-hidden">
    
    <nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm fixed w-full z-50 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('assets/images/NEO.png') }}" alt="NEO Logo" class="h-12 w-auto object-contain">
                    <div class="text-3xl font-bold gradient-text tracking-tight font-space-grotesk">
                        Telemetri
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 px-4 py-2 rounded-md text-sm font-semibold transition-colors duration-300">
                        Login
                    </a>
                    <a href="#features" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors duration-300 hover:bg-blue-700">
                        Fitur
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:items-center lg:justify-between lg:space-x-16">
                <div class="lg:w-1/2 mb-16 lg:mb-0">
                    <div class="space-y-8">
                        <div>
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 dark:text-white leading-tight mb-8 text-shadow font-space-grotesk">
                                Manajemen 
                                <span class="gradient-text relative">
                                    Absensi
                                </span> 
                                Modern
                            </h1>
                        </div>
                        
                        <div>
                            <p class="text-xl text-gray-600 dark:text-gray-300 mb-12 max-w-2xl leading-relaxed">
                                <span class="font-semibold text-blue-600 dark:text-blue-400">Neo Telemetri</span>: 
                                Solusi digital untuk absensi, rapat, dan penjadwalan piket organisasi dengan 
                                <span class="font-semibold">efisiensi maksimal</span>.
                            </p>
                        </div>
                        
                        <div>
                            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6">
                                <a href="{{ route('login') }}" class="inline-flex justify-center px-8 py-4 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg transition-all duration-300">
                                    <span class="flex items-center space-x-2">
                                        <span>Masuk ke Dashboard</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 flex justify-center">
                    <div class="relative w-full max-w-2xl">
                        <div class="relative bg-white/50 dark:bg-gray-800/50 rounded-3xl p-4 shadow-xl">
                            <img class="rounded-2xl shadow-lg w-full h-auto object-cover" 
                                 src="{{ asset('assets/images/dashboard-preview.jpg') }}" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'" 
                                 alt="Dashboard Preview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <div>
                    <span class="inline-block px-6 py-2 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-full text-sm font-semibold mb-6">
                        Fitur Unggulan
                    </span>
                    <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 font-space-grotesk">
                        Teknologi <span class="gradient-text">Terdepan</span>
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                        Transformasi manajemen organisasi Anda dengan solusi inovatif yang dirancang untuk efisiensi.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="bg-slate-50 dark:bg-gray-800/50 rounded-3xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Manajemen Presensi</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Pantau kehadiran secara real-time dengan teknologi QR Code untuk kegiatan dan rapat.</p>
                </div>

                <div class="bg-slate-50 dark:bg-gray-800/50 rounded-3xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Jadwal Piket</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Atur dan koordinasikan piket anggota dengan fleksibilitas tinggi dan kemudahan delegasi.</p>
                </div>

                <div class="bg-slate-50 dark:bg-gray-800/50 rounded-3xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Manajemen Perizinan</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Kontrol perizinan anggota dengan sistem yang terpusat dan mudah dikelola oleh administrator.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-blue-600 dark:bg-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-white mb-8 text-shadow font-space-grotesk">
                    Mulai Transformasi <span class="text-yellow-300">Sekarang</span>
                </h2>
                <p class="text-xl text-blue-100 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Bergabunglah dengan organisasi yang telah merasakan efisiensi luar biasa dengan Telemetri.
                </p>
                
                <a href="{{ route('login') }}" class="inline-flex justify-center px-10 py-5 bg-white text-blue-600 font-bold text-xl rounded-xl hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/50 shadow-xl transition-all duration-300">
                    <span class="flex items-center space-x-3">
                        <span>Masuk Sekarang</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 dark:bg-gray-950 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12 text-center md:text-left">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-4 mb-6 justify-center md:justify-start">
                        <img src="{{ asset('assets/images/NEO.png') }}" alt="NEO Logo" class="h-12 w-auto">
                        <span class="text-3xl font-bold text-white font-space-grotesk">Telemetri</span>
                    </div>
                    <p class="text-gray-300 text-lg max-w-md mx-auto md:mx-0">
                        Solusi digital terdepan untuk manajemen organisasi modern.
                    </p>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-white mb-6">Navigasi</h5>
                    <ul class="space-y-4">
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-300">Login</a></li>
                        <li><a href="#features" class="text-gray-300 hover:text-white transition-colors duration-300">Fitur</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-white mb-6">Dukungan</h5>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Bantuan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-700/50 text-center">
                <p class="text-gray-400 text-sm">&copy; 2025 Neo Telemetri. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>