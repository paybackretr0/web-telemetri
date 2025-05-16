<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        @if(isset($search))
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                   wire:model.debounce.300ms="search" 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-900" 
                   placeholder="Cari data...">
        </div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs font-medium text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    {{ $header }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($pagination))
    <div class="p-4 bg-white border-t border-gray-200">
        {{ $pagination }}
    </div>
    @endif
</div>