<div class="modal fade" id="addAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue-600">
                <h5 class="modal-title text-white" id="addAttendanceModalLabel">Tambah Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addAttendanceForm" action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <select id="type" name="type" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="activity">Kegiatan</option>
                            <option value="meeting">Rapat</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea class="form-textarea rounded-md shadow-sm mt-1 block w-full" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full" id="location" name="location" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group mb-4">
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" class="form-input rounded-md shadow-sm mt-1 block w-full" id="start_time" name="start_time" required>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                            <input type="datetime-local" class="form-input rounded-md shadow-sm mt-1 block w-full" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4" id="attendance_type_container">
                        <label for="attendance_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kehadiran</label>
                        <select id="attendance_type_id" name="attendance_type_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                            @foreach($attendanceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" id="generate_qr" name="generate_qr" checked>
                            <label for="generate_qr" class="ml-2 block text-sm text-gray-700">Generate QR Code untuk Absensi</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">QR Code akan otomatis dibuat dan dapat digunakan untuk absensi via aplikasi mobile</p>
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

<script>
    // Toggle tampilan tipe kehadiran berdasarkan tipe yang dipilih
    document.getElementById('type').addEventListener('change', function() {
        const attendanceTypeContainer = document.getElementById('attendance_type_container');
        const generateQrContainer = document.getElementById('generate_qr').closest('.form-group');
        
        if (this.value === 'meeting') {
            attendanceTypeContainer.style.display = 'none';
            generateQrContainer.style.display = 'none';
        } else {
            attendanceTypeContainer.style.display = 'block';
            generateQrContainer.style.display = 'block';
        }
    });
</script>