<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-auto transform transition-all duration-300 scale-95 opacity-0" id="editModalContainer">
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-2xl">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Kegiatan/Rapat
            </h3>
            <button class="text-white hover:text-gray-200 p-1 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200" onclick="closeEditModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="editModalLoader" class="hidden p-8 text-center">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600 text-sm">Memuat data...</span>
            </div>
        </div>
        <form id="editAttendanceForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <input type="hidden" id="edit_type" name="type">
            <div id="editModalContent" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title Field -->
                    <div class="relative">
                        <input type="text" id="edit_title" name="title" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required placeholder=" ">
                        <label for="edit_title" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-600">Judul</label>
                        <p id="edit_title_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Location Field -->
                    <div class="relative">
                        <input type="text" id="edit_location" name="location" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required placeholder=" ">
                        <label for="edit_location" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-600">Lokasi</label>
                        <p id="edit_location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Date Field -->
                    <div class="relative">
                        <input type="date" id="edit_event_date" name="event_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                        <label for="edit_event_date" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Tanggal</label>
                        <p id="edit_event_date_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Start Time Field -->
                    <div class="relative">
                        <input type="time" id="edit_start_time" name="start_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                        <label for="edit_start_time" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Jam Mulai</label>
                        <p id="edit_start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- End Time Field -->
                    <div class="relative">
                        <input type="time" id="edit_end_time" name="end_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                        <label for="edit_end_time" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Jam Selesai</label>
                        <p id="edit_end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Attendance Type Field (Activity Only) -->
                    <div id="edit_attendance_type_container" class="relative hidden md:col-span-2">
                        <label for="edit_attendance_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kehadiran</label>
                        <select id="edit_attendance_type_id" name="attendance_type_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            @foreach($attendanceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <p id="edit_attendance_type_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="relative">
                    <textarea id="edit_description" name="description" rows="5" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" placeholder=" "></textarea>
                    <label for="edit_description" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-placeholder-shown:-translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-600">Deskripsi</label>
                    <p id="edit_description_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- QR Code Section (Activity Only) -->
                <div id="qr_code_section" class="p-4 border border-gray-200 rounded-lg bg-gray-50 space-y-3 hidden">
                    <h6 class="text-sm font-medium text-gray-700">QR Code</h6>
                    <div id="qr_code_status" class="text-sm"></div>
                    <div id="qr_code_actions" class="flex items-center space-x-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="regenerate_qr" name="regenerate_qr" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-200 rounded transition-colors duration-200">
                            <span class="ml-2 text-sm text-gray-700">Regenerate QR Code</span>
                        </label>
                        <button type="button" id="view_qr_btn" onclick="viewQrCode(document.getElementById('edit_id').value)" class="px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 focus:ring-2 focus:ring-indigo-400 transition-all duration-200 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 p-6 pt-0 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush