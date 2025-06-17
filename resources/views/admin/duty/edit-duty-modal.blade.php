<!-- Edit Duty Modal -->
<div id="editDutyModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="editModalContainer">
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t-xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Jadwal Piket
            </h3>
            <button type="button" class="text-white hover:bg-white/10 p-2 rounded-full transition-colors" onclick="closeEditModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="editModalLoader" class="hidden p-8 text-center">
            <div class="flex items-center justify-center gap-3">
                <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span class="text-sm text-gray-600">Memuat data...</span>
            </div>
        </div>
        <form id="editDutyForm" method="POST" action="" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div id="editModalContent" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Day of Week -->
                    <div class="relative">
                        <select id="edit_day_of_week" name="day_of_week" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                            <option value="" disabled>Pilih Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                        <label for="edit_day_of_week" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Hari</label>
                        <p id="edit_day_of_week_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <!-- Location -->
                    <div class="relative">
                        <input type="text" id="edit_location" name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                        <label for="edit_location" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Lokasi</label>
                        <p id="edit_location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <!-- Start Time -->
                    <div class="relative">
                        <input type="time" id="edit_start_time" name="start_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                        <label for="edit_start_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Mulai</label>
                        <p id="edit_start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <!-- End Time -->
                    <div class="relative">
                        <input type="time" id="edit_end_time" name="end_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                        <label for="edit_end_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Selesai</label>
                        <p id="edit_end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>
                <!-- Users Selection -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Petugas</label>
                    <div id="edit_users_container" class="space-y-4"></div>
                    <button type="button" id="editAddUserBtn" class="mt-3 text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Petugas
                    </button>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 pt-0 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>