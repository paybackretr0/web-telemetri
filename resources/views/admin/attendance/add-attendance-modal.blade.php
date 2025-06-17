<!-- Add Attendance Modal -->
<div id="addAttendanceModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95" id="addModalContainer">
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t-xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Kegiatan/Rapat
            </h3>
            <button class="text-white hover:bg-white/10 p-2 rounded-full transition-colors" onclick="closeAddModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="addAttendanceForm" action="{{ route('attendance.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title Field -->
                <div class="relative">
                    <input type="text" id="title" name="title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="title" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600 peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400">Judul</label>
                    <p id="title_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- Location Field -->
                <div class="relative">
                    <input type="text" id="location" name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="location" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600 peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400">Lokasi</label>
                    <p id="location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- Attendance Type Field (Activity Only) -->
                <div id="attendance_type_container" class="relative hidden">
                    <select id="attendance_type_id" name="attendance_type_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer bg-white">
                        @foreach($attendanceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <label for="attendance_type_id" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Tipe Kehadiran</label>
                    <p id="attendance_type_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- Date Field -->
                <div class="relative">
                    <input type="date" id="event_date" name="event_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="event_date" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Tanggal</label>
                    <p id="event_date_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- Start Time Field -->
                <div class="relative">
                    <input type="time" id="start_time" name="start_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="start_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Mulai</label>
                    <p id="start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- End Time Field -->
                <div class="relative">
                    <input type="time" id="end_time" name="end_time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer" required>
                    <label for="end_time" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600">Jam Selesai</label>
                    <p id="end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
            </div>

            <!-- Description Field -->
            <div class="relative">
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all peer"></textarea>
                <label for="description" class="absolute left-3 -top-2.5 text-sm font-medium text-gray-600 bg-white px-1 transition-all peer-focus:text-indigo-600 peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400">Deskripsi</label>
                <p id="description_error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <!-- Generate QR Code (Activity Only) -->
            <div id="generate_qr_container" class="flex items-center gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="generate_qr" name="generate_qr" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                    <span class="ml-2 text-sm text-gray-700">Generate QR Code untuk Absensi</span>
                </label>
                <p class="text-xs text-gray-500">QR Code akan otomatis dibuat untuk absensi via aplikasi mobile</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
@endpush