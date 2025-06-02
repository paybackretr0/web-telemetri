@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600 mt-1">Berikut ringkasan aktivitas Neo Telemetri hari ini.</p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengurus</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kegiatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">{{ $todayActivities ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Piket Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">{{ $todayDuties ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Perizinan Pending</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">{{ $pendingPermissions ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('attendance.index') }}" class="group bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:bg-blue-50 transition-all duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-all duration-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600">Kegiatan & Rapat</h3>
                    <p class="text-sm text-gray-600">Kelola presensi kegiatan dan rapat</p>
                </div>
            </div>
        </a>

        <a href="{{ route('duty.index') }}" class="group bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:bg-blue-50 transition-all duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-all duration-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600">Jadwal Piket</h3>
                    <p class="text-sm text-gray-600">Atur jadwal piket pengurus</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permissions.index') }}" class="group bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:bg-blue-50 transition-all duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-all duration-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600">Perizinan</h3>
                    <p class="text-sm text-gray-600">Kelola perizinan pengurus</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection