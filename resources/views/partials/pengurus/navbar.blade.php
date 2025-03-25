<nav class="bg-white dark:bg-gray-800 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-3">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-semibold text-gray-800 dark:text-white">
                    Telemetri
                </a>
                
                <div class="hidden md:flex items-center ml-10 space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        Dashboard
                    </a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md">
                        Activities
                    </a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md">
                        Calendar
                    </a>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="button" class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>
                
                <div class="relative">
                    <button type="button" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="{{ Auth::user()->name }}">
                        <span class="hidden md:inline-block">{{ Auth::user()->name }}</span>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>