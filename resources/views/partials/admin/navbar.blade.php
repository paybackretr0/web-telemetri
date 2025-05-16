<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50 w-full">
    <div class="px-6 py-3 flex justify-end items-center">
        <div class="flex items-center space-x-5">
            <div class="relative">
                <button type="button" class="text-gray-500 hover:text-blue-600 focus:outline-none p-1 rounded-full hover:bg-blue-50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
            </div>
            <div class="relative">
                <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 focus:outline-none p-1 rounded-full hover:bg-blue-50 transition-colors">
                    <img class="h-9 w-9 rounded-full object-cover border-2 border-transparent group-hover:border-blue-300 transition" src="{{ $user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=EBF4FF&color=3B82F6&font-size=0.5&bold=true' }}" alt="{{ Auth::user()->name }}">
                    <span class="hidden md:inline-block font-medium">{{ Auth::user()->name }}</span>
                </button>
            </div>
        </div>
    </div>
</nav>