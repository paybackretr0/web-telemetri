@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-4">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Admin Dashboard</h2>
        <p class="text-gray-600 dark:text-gray-400">Welcome to the admin dashboard, {{ Auth::user()->name }}!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Users</h3>
            <p class="text-gray-600 dark:text-gray-400">Manage system users and their permissions.</p>
            <div class="mt-4">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    View Users
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Activities</h3>
            <p class="text-gray-600 dark:text-gray-400">Manage and monitor all system activities.</p>
            <div class="mt-4">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    View Activities
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">Settings</h3>
            <p class="text-gray-600 dark:text-gray-400">Configure system settings and preferences.</p>
            <div class="mt-4">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Manage Settings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection