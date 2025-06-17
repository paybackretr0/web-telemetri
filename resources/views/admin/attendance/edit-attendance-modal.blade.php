<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95" id="editModalContainer">
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t-xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Kegiatan/Rapat
            </h3>
            <button class="text-white hover:bg-white/10 p-2 rounded-full transition-colors" onclick="closeEditModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="editModalLoader" class="hidden p-8 text-center">
            <div class="flex items-center gap-3">
                <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span class="text-sm text-gray-600">Memuat data...</span>
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
                        <input type="text" id="edit_title" name="title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                        <label for="edit_title" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600 peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400">Judul</label>
                        <p id="edit_title_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Location Field -->
                    <div class="relative">
                        <input type="text" id="edit_location" name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                        <label for="edit_location" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600 peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400">Lokasi</label>
                        <p id="edit_location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Date Field -->
                    <div class="relative">
                        <input type="date" id="edit_event_date" name="event_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                        <label for="edit_event_date" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Tanggal</label>
                        <p id="edit_event_date_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Start Time Field -->
                    <div class="relative">
                        <input type="time" id="edit_start_time" name="start_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                        <label for="edit_start_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Mulai</label>
                        <p id="edit_start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- End Time Field -->
                    <div class="relative">
                        <input type="time" id="edit_end_time" name="end_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                        <label for="edit_end_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Selesai</label>
                        <p id="edit_end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Attendance Type Field (Activity Only) -->
                    <div id="edit_attendance_type_container" class="relative hidden">
                        <select id="edit_attendance_type_id" name="attendance_type_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer bg-white">
                            @foreach($attendanceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <label for="edit_attendance_type_id" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Tipe Kehadiran</label>
                        <p id="edit_attendance_type_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="relative">
                    <textarea id="edit_description" name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer"></textarea>
                    <label for="edit_description" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600 peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400">Deskripsi</label>
                    <p id="edit_description_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- QR Code Section (Activity Only) -->
                <div id="qr_code_section" class="p-4 border border-gray-200 rounded-lg bg-gray-50 space-y-3 hidden">
                    <h6 class="text-sm font-medium text-gray-700">QR Code</h6>
                    <div id="qr_code_status" class="text-sm text-gray-600"></div>
                    <div id="qr_code_actions" class="flex items-center gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="regenerate_qr" name="regenerate_qr" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Regenerate QR Code</span>
                        </label>
                    </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
@endpush