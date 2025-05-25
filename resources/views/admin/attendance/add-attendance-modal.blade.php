<!-- Add Attendance Modal -->
<div id="addAttendanceModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-auto transform transition-all duration-300 scale-95 opacity-0" id="addModalContainer">
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-2xl">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kegiatan/Rapat
            </h3>
            <button class="text-white hover:text-gray-200 p-1 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200" onclick="closeAddModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="addAttendanceForm" action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type Field -->
                    <div class="relative">
                        <select id="type" name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                            <option value="activity">Kegiatan</option>
                            <option value="meeting">Rapat</option>
                        </select>
                        <label for="type" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Tipe</label>
                        <p id="type_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Title Field -->
                    <div class="relative">
                        <input type="text" id="title" name="title" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required placeholder=" ">
                        <label for="title" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-600">Judul</label>
                        <p id="title_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Location Field -->
                    <div class="relative">
                        <input type="text" id="location" name="location" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required placeholder=" ">
                        <label for="location" class="absolute left-4 top-2.5 text-gray-600 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-placeholder-shown:-translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-600">Lokasi</label>
                        <p id="location_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Attendance Type Field (Activity Only) -->
                    <div id="attendance_type_container" class="relative">
                        <select id="attendance_type_id" name="attendance_type_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($attendanceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <label for="attendance_type_id" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Tipe Kehadiran</label>
                        <p id="attendance_type_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Date Field -->
                    <div class="relative">
                        <input type="date" id="event_date" name="event_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                        <label for="event_date" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Tanggal</label>
                        <p id="event_date_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Start Time Field -->
                    <div class="relative">
                        <input type="time" id="start_time" name="start_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                        <label for="start_time" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Jam Mulai</label>
                        <p id="start_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- End Time Field -->
                    <div class="relative">
                        <input type="time" id="end_time" name="end_time" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" required>
                        <label for="end_time" class="absolute left-4 top-2.5 text-gray-500 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-focus:text-indigo-600">Jam Selesai</label>
                        <p id="end_time_error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="relative">
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-colors duration-200" placeholder=" "></textarea>
                    <label for="description" class="absolute left-4 top-2.5 text-gray-600 text-sm transition-all duration-200 transform -translate-y-4 scale-75 origin-top-left peer-details-shown:-translate-y-0 peer-details-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-600">Deskripsi</label>
                    <p id="description_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- Generate QR Code (Activity Only) -->
                <div id="generate_qr_container" class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="generate_qr" name="generate_qr" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-200 rounded transition-colors duration-200" checked>
                        <span class="ml-2 text-sm text-gray-700">Generate QR Code untuk Absensi</span>
                    </label>
                    <p class="text-xs text-gray-500 ml-4">QR Code akan otomatis dibuat untuk absensi via aplikasi mobile</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3 p-6 pt-0 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition-all duration-200 font-medium flex items-center text-sm">
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