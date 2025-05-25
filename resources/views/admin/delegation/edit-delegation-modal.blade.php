<!-- Edit Duty Modal -->
<div id="editDutyModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-auto transform transition-all duration-300 scale-95 opacity-0" id="editModalContainer">
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-2xl">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Jadwal Piket
            </h3>
            <button type="button" class="text-white hover:text-gray-200 p-1 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200" onclick="closeEditModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="editModalLoader" class="hidden p-8 text-center">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600 text-sm">Memuat data...</span>
            </div>
        </div>
        <form id="editDutyForm" method="POST" action="" onsubmit="return false;">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div id="editModalContent" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Day of Week -->
                    <div class="relative">
                        <select id="edit_day_of_week" name="day_of_week" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer transition-colors duration-200" required>
                            <option value="" disabled>Pilih Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                        <label for="edit_day_of_week" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-blue-600">Hari</label>
                        <p id="edit_day_of_week_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Location -->
                    <div class="relative">
                        <input type="text" id="edit_location" name="location" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer transition-colors duration-200" required>
                        <label for="edit_location" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-blue-600">Lokasi</label>
                        <p id="edit_location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Start Time -->
                    <div class="relative">
                        <input type="time" id="edit_start_time" name="start_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer transition-colors duration-200" required>
                        <label for="edit_start_time" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-blue-600">Waktu Mulai</label>
                        <p id="edit_start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- End Time -->
                    <div class="relative">
                        <input type="time" id="edit_end_time" name="end_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer transition-colors duration-200" required>
                        <label for="edit_end_time" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-blue-600">Waktu Selesai</label>
                        <p id="edit_end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>

                <!-- Users Selection -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Petugas</label>
                    <div id="edit_users_container" class="space-y-4"></div>
                    <button type="button" id="editAddUserBtn" class="mt-3 text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Petugas
                    </button>
                </div>
            </div>
            <div class="flex justify-end space-x-3 p-6 pt-0 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>