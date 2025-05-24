<div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue-600">
                <h5 class="modal-title text-white" id="editAttendanceModalLabel">Edit Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAttendanceForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <input type="hidden" id="edit_type" name="type">
                
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full" id="edit_title" name="title" required>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea class="form-textarea rounded-md shadow-sm mt-1 block w-full" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="edit_location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full" id="edit_location" name="location" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group mb-4">
                            <label for="edit_start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" class="form-input rounded-md shadow-sm mt-1 block w-full" id="edit_start_time" name="start_time" required>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="edit_end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                            <input type="datetime-local" class="form-input rounded-md shadow-sm mt-1 block w-full" id="edit_end_time" name="end_time" required>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4" id="edit_attendance_type_container">
                        <label for="edit_attendance_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kehadiran</label>
                        <select id="edit_attendance_type_id" name="attendance_type_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                            @foreach($attendanceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="qr_code_section" class="mt-4 p-4 border rounded-lg bg-gray-50">
                        <h6 class="font-medium text-gray-700 mb-2">QR Code</h6>
                        <div id="qr_code_status"></div>
                        
                        <div id="qr_code_actions" class="mt-3">
                            <button type="button" id="regenerate_qr" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Regenerate QR Code
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QR Code View Modal -->
<div class="modal fade" id="viewQrModal" tabindex="-1" role="dialog" aria-labelledby="viewQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue-600">
                <h5 class="modal-title text-white" id="viewQrModalLabel">QR Code Absensi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeImage" class="mb-3"></div>
                <p id="qrActivityTitle" class="font-medium"></p>
                <p id="qrExpiry" class="text-sm text-gray-500"></p>
                <div class="mt-3">
                    <button id="downloadQrBtn" class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download QR
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>