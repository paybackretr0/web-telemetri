@extends('layouts.admin')

@section('title', 'Kegiatan & Rapat')

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">Data Kegiatan & Rapat</h2>
            <div class="flex space-x-3">
                <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center" data-toggle="modal" data-target="#addAttendanceModal">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kegiatan
                </a>
            </div>
        </div>

        <div x-data="{ activeTab: 'activities' }" class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button
                        @click="activeTab = 'activities'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'activities', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'activities' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Kegiatan
                    </button>
                    <button
                        @click="activeTab = 'meetings'"
                        :class="{ 'border-purple-500 text-purple-600': activeTab === 'meetings', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'meetings' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Rapat
                    </button>
                </nav>
            </div>

            <div x-show="activeTab === 'activities'" class="mt-6">
                <x-table :search="true">
                    <x-slot name="header">
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Judul</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tipe</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">QR Code</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Dibuat Oleh</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                    </x-slot>

                    @forelse ($activities as $activity)
                        <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out" data-id="{{ $activity->id }}" data-type="activity">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $activity->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-lg">Kegiatan</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->location }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->start_time->format('d M Y H:i') }} -
                                {{ $activity->end_time->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($activity->qrCode)
                                    <button class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group" onclick="viewQrCode({{ $activity->id }})">
                                        <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                        <span class="group-hover:text-blue-700">Lihat QR</span>
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->creator->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="#" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-150 group" onclick="openEditModal({{ $activity->id }}, 'activity')">
                                        <svg class="w-4 h-4 mr-2 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="group-hover:text-green-700">Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center py-10">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-lg">Tidak ada data kegiatan yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
                <div class="mt-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center text-sm text-gray-500">
                        <span>Menampilkan</span>
                        <span class="font-semibold mx-1">{{ $activities->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-semibold mx-1">{{ $activities->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-semibold mx-1">{{ $activities->total() }}</span>
                        <span>data kegiatan</span>
                    </div>
                    {{ $activities->links() }}
                </div>
            </div>

            <div x-show="activeTab === 'meetings'" class="mt-6">
                <x-table :search="true">
                    <x-slot name="header">
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Judul</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tipe</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">QR Code</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Dibuat Oleh</th>
                        <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                    </x-slot>

                    @forelse ($meetings as $meeting)
                        <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out" data-id="{{ $meeting->id }}" data-type="meeting">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $meeting->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-lg">Rapat</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $meeting->location }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $meeting->start_time->format('d M Y H:i') }} -
                                {{ $meeting->end_time->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                -
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $meeting->creator->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="#" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-150 group" onclick="openEditModal({{ $meeting->id }}, 'meeting')">
                                        <svg class="w-4 h-4 mr-2 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="group-hover:text-green-700">Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @if($activities->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center py-10">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-lg">Tidak ada data rapat yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforelse
                </x-table>
                <div class="mt-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center text-sm text-gray-500">
                        <span>Menampilkan</span>
                        <span class="font-semibold mx-1">{{ $meetings->firstItem() ?? 0 }}</span>
                        <span>sampai</span>
                        <span class="font-semibold mx-1">{{ $meetings->lastItem() ?? 0 }}</span>
                        <span>dari</span>
                        <span class="font-semibold mx-1">{{ $meetings->total() }}</span>
                        <span>data rapat</span>
                    </div>
                    {{ $meetings->links() }}
                </div>
            </div>
        </div>
    </div>
        <!-- Tambahkan modal di sini -->
    @include('admin.attendance.add-attendance-modal')
    @include('admin.attendance.edit-attendance-modal')

    <script>
        // Fungsi untuk membuka modal tambah kegiatan
        function openAddModal() {
            document.getElementById('add-attendance-modal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal tambah kegiatan
        function closeAddModal() {
            document.getElementById('add-attendance-modal').classList.add('hidden');
        }

        // Fungsi untuk membuka modal edit kegiatan
        function openEditModal(id, type) {
            const modal = document.getElementById('edit-attendance-modal');
            const form = document.getElementById('edit-attendance-form');
            const typeContainer = document.getElementById('edit_attendance_type_container');
            const qrContainer = document.getElementById('qr_code_container');

            // Set action URL berdasarkan tipe
            form.action = `/admin/attendance/${id}/${type}`;
            
            // Set hidden input values
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_model_type').value = type;

            // Tampilkan/sembunyikan elemen berdasarkan tipe
            if (type === 'meeting') {
                typeContainer.classList.add('hidden');
                qrContainer.classList.add('hidden');
            } else {
                typeContainer.classList.remove('hidden');
                qrContainer.classList.remove('hidden');
            }

            // Ambil data dari server
            fetch(`/admin/attendance/${id}/${type}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Isi form dengan data yang diterima
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_description').value = data.description || '';
                    document.getElementById('edit_location').value = data.location;
                    
                    // Format tanggal dan waktu untuk input datetime-local
                    const startTime = new Date(data.start_time);
                    const endTime = new Date(data.end_time);
                    
                    document.getElementById('edit_start_time').value = formatDateTimeForInput(startTime);
                    document.getElementById('edit_end_time').value = formatDateTimeForInput(endTime);
                    
                    if (type === 'activity' && data.attendance_type_id) {
                        document.getElementById('edit_attendance_type_id').value = data.attendance_type_id;
                    }
                    
                    // Tampilkan modal
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
                });
        }

        // Fungsi untuk menutup modal edit kegiatan
        function closeEditModal() {
            document.getElementById('edit-attendance-modal').classList.add('hidden');
        }

        // Fungsi untuk memformat tanggal untuk input datetime-local
        function formatDateTimeForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Fungsi untuk melihat QR Code
        function viewQrCode(id) {
            // Buka modal QR code atau tampilkan di halaman baru
            window.open(`/admin/attendance/${id}/qrcode`, '_blank', 'width=500,height=500');
        }

        // Tambahkan event listener untuk tombol tambah kegiatan
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.querySelector('.bg-blue-600.text-white');
            if (addButton) {
                addButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    openAddModal();
                });
            }

            // Tambahkan event listener untuk tombol edit
            const editButtons = document.querySelectorAll('.bg-green-50.text-green-600');
            editButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const id = row.dataset.id;
                    const type = row.dataset.type;
                    openEditModal(id, type);
                });
            });

            // Tambahkan event listener untuk tombol lihat QR
            const qrButtons = document.querySelectorAll('.bg-blue-50.text-blue-600');
            qrButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const id = row.dataset.id;
                    viewQrCode(id);
                });
            });
        });
    </script>

    <script>
        // Fungsi untuk melihat QR Code
        function viewQrCode(id) {
            // Buka modal QR code
            $('#viewQrModal').modal('show');
            
            // Ambil data QR code dari server
            fetch(`/admin/attendance/${id}/qrcode-data`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Tampilkan QR code dan informasi terkait
                    document.getElementById('qrCodeImage').innerHTML = `<img src="${data.qr_image_url}" class="mx-auto" alt="QR Code">`;
                    document.getElementById('qrActivityTitle').textContent = data.title;
                    document.getElementById('qrExpiry').textContent = `Berlaku hingga: ${data.expiry_time}`;
                    
                    // Set URL untuk download
                    document.getElementById('downloadQrBtn').onclick = function() {
                        window.location.href = `/admin/attendance/${id}/qrcode-download`;
                    };
                })
                .catch(error => {
                    console.error('Error fetching QR code:', error);
                    alert('Terjadi kesalahan saat mengambil QR code. Silakan coba lagi.');
                });
        }

        // Fungsi untuk membuka modal edit kegiatan
        function openEditModal(id, type) {
            // Reset form
            document.getElementById('editAttendanceForm').reset();
            
            // Set action URL dan hidden fields
            const form = document.getElementById('editAttendanceForm');
            form.action = `/admin/attendance/${id}`;
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_type').value = type;
            
            // Tampilkan/sembunyikan elemen berdasarkan tipe
            const typeContainer = document.getElementById('edit_attendance_type_container');
            const qrSection = document.getElementById('qr_code_section');
            
            if (type === 'meeting') {
                typeContainer.style.display = 'none';
                qrSection.style.display = 'none';
            } else {
                typeContainer.style.display = 'block';
                qrSection.style.display = 'block';
            }
            
            // Ambil data dari server
            fetch(`/admin/attendance/${id}/edit?type=${type}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Isi form dengan data yang diterima
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_description').value = data.description || '';
                    document.getElementById('edit_location').value = data.location;
                    
                    // Format tanggal dan waktu untuk input datetime-local
                    document.getElementById('edit_start_time').value = formatDateTimeForInput(new Date(data.start_time));
                    document.getElementById('edit_end_time').value = formatDateTimeForInput(new Date(data.end_time));
                    
                    if (type === 'activity' && data.attendance_type_id) {
                        document.getElementById('edit_attendance_type_id').value = data.attendance_type_id;
                    }
                    
                    // Update QR code status
                    if (type === 'activity') {
                        const qrStatus = document.getElementById('qr_code_status');
                        if (data.qr_code) {
                            qrStatus.innerHTML = `
                                <div class="flex items-center text-green-600 mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>QR Code telah dibuat</span>
                                </div>
                                <p class="text-xs text-gray-500">Berlaku hingga: ${new Date(data.qr_code.expiry_time).toLocaleString()}</p>
                            `;
                        } else {
                            qrStatus.innerHTML = `
                                <div class="flex items-center text-yellow-600 mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span>QR Code belum dibuat</span>
                                </div>
                            `;
                        }
                    }
                    
                    // Tampilkan modal
                    $('#editAttendanceModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
                });
        }

        // Fungsi untuk memformat tanggal untuk input datetime-local
        function formatDateTimeForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }
        
        // Event handler untuk regenerate QR code
        document.getElementById('regenerate_qr').addEventListener('click', function() {
            const id = document.getElementById('edit_id').value;
            
            if (!id) return;
            
            fetch(`/admin/attendance/${id}/regenerate-qr`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const qrStatus = document.getElementById('qr_code_status');
                    qrStatus.innerHTML = `
                        <div class="flex items-center text-green-600 mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>QR Code berhasil dibuat ulang</span>
                        </div>
                        <p class="text-xs text-gray-500">Berlaku hingga: ${new Date(data.qr_code.expiry_time).toLocaleString()}</p>
                    `;
                    alert('QR Code berhasil dibuat ulang!');
                } else {
                    alert('Gagal membuat ulang QR Code. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error regenerating QR code:', error);
                alert('Terjadi kesalahan saat membuat ulang QR code. Silakan coba lagi.');
            });
        });
    </script>
@endsection
