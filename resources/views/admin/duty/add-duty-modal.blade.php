<!-- Add Duty Modal -->
<div id="addDutyModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="addModalContainer">
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t-xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Jadwal Piket
            </h3>
            <button type="button" class="text-white hover:bg-white/10 p-2 rounded-full transition-colors" onclick="closeAddModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="addDutyForm" action="{{ route('duty.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Day of Week -->
                <div class="relative">
                    <select id="day_of_week" name="day_of_week" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer bg-white" required>
                        <option value="" disabled selected>Pilih Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    <label for="day_of_week" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Hari</label>
                    <p id="day_of_week_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <!-- Location -->
                <div class="relative">
                    <input type="text" id="location" name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="location" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Lokasi</label>
                    <p id="location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <!-- Start Time -->
                <div class="relative">
                    <input type="time" id="start_time" name="start_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="start_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Mulai</label>
                    <p id="start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <!-- End Time -->
                <div class="relative">
                    <input type="time" id="end_time" name="end_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="end_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Selesai</label>
                    <p id="end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
            </div>
            <!-- Users Selection -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Petugas</label>
                <div id="users_container" class="space-y-4">
                    <div class="user_entry border p-4 rounded-lg bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <select name="users[0][id]" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="" disabled selected>Pilih Petugas</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="users[0][start_date]" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <input type="date" name="users[0][end_date]" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <p class="user_error text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>
                <button type="button" id="addUserBtn" class="mt-3 text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Petugas
                </button>
            </div>
            <div class="flex justify-end gap-3 p-6 pt-0 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>