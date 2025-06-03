@extends('layouts.admin')

@section('title', 'Kegiatan & Rapat')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">Data Kegiatan & Rapat</h2>
        <div class="flex space-x-3">
            <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kegiatan
            </button>
        </div>
    </div>

    <div x-data="{ activeTab: 'activities' }" class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'activities'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'activities', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'activities' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Kegiatan
                </button>
                <button @click="activeTab = 'meetings'" :class="{ 'border-purple-500 text-purple-600': activeTab === 'meetings', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'meetings' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $activity->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-lg">Kegiatan</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->start_time->format('d M Y H:i') }} - {{ $activity->end_time->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($activity->qrCode)
                                <button onclick="viewQrCode({{ $activity->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group">
                                    <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    <span class="group-hover:text-blue-700">Lihat QR</span>
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->creator->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <button onclick="openEditModal({{ $activity->id }}, 'activity')" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-150 group">
                                    <svg class="w-4 h-4 mr-2 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="group-hover:text-green-700">Edit</span>
                                </button>
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
                @if ($activities->hasPages())
                <x-slot name="pagination">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if (!$activities->onFirstPage())
                                <a href="{{ $activities->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            @endif
                            
                            <span class="text-sm text-gray-700">
                                Halaman {{ $activities->currentPage() }} dari {{ $activities->lastPage() }}
                            </span>
                            
                            @if ($activities->hasMorePages())
                                <a href="{{ $activities->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $activities->firstItem() ?? 0 }} - {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} data
                        </div>
                    </div>
                </x-slot>
            @endif
            </x-table>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $meeting->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-lg">Rapat</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $meeting->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $meeting->meeting_date->format('d M Y')  }} {{ $meeting->start_time->format('H:i') }} - {{ $meeting->end_time->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($meeting->qrCode)
                                <button onclick="viewQrCode({{ $meeting->id }}, 'meeting')" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group">
                                    <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    <span class="group-hover:text-blue-700">Lihat QR</span>
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $meeting->creator->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <button onclick="openEditModal({{ $meeting->id }}, 'meeting')" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-150 group">
                                    <svg class="w-4 h-4 mr-2 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="group-hover:text-green-700">Edit</span>
                                </button>
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
                                <p class="text-lg">Tidak ada data rapat yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                @if ($meetings->hasPages())
                <x-slot name="pagination">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if (!$meetings->onFirstPage())
                                <a href="{{ $meetings->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            @endif
                            
                            <span class="text-sm text-gray-700">
                                Halaman {{ $meetings->currentPage() }} dari {{ $meetings->lastPage() }}
                            </span>
                            
                            @if ($meetings->hasMorePages())
                                <a href="{{ $meetings->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $meetings->firstItem() ?? 0 }} - {{ $meetings->lastItem() ?? 0 }} dari {{ $meetings->total() }} data
                        </div>
                    </div>
                </x-slot>
            @endif
            </x-table>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto transform transition-all duration-300 scale-95 opacity-0" id="qrModalContainer">
            <div class="flex items-center justify-between p-5 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-2xl">
                <h3 class="text-lg font-semibold text-white">QR Code</h3>
                <button class="text-white hover:text-gray-200 p-1 rounded-full hover:bg-white hover:bg-opacity-10" onclick="closeQrCodeModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 text-center">
                <img id="qrCodeImage" src="" alt="QR Code" class="mx-auto mb-4">
                <p id="qrCodeExpiry" class="text-sm text-gray-600"></p>
            </div>
            <div class="flex justify-end p-6 pt-0 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeQrCodeModal()" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Tutup</button>
            </div>
        </div>
    </div>
</div>
@include('admin.attendance.add-attendance-modal')
@include('admin.attendance.edit-attendance-modal')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const generateQrCheckbox = document.getElementById('generate_qr');
    const qrExpiryContainer = document.getElementById('qr_expiry_container');
    
    if (generateQrCheckbox && qrExpiryContainer) {
        generateQrCheckbox.addEventListener('change', function() {
            qrExpiryContainer.classList.toggle('hidden', !this.checked);
        });
        // Initial state
        qrExpiryContainer.classList.toggle('hidden', !generateQrCheckbox.checked);
    }
    // Add form submission
    const addForm = document.getElementById('addAttendanceForm');
    if (addForm) {
        addForm.removeEventListener('submit', handleAddFormSubmit); // Pastikan tidak ada listener ganda
        addForm.addEventListener('submit', handleAddFormSubmit);
    }

    function handleAddFormSubmit(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = document.getElementById('addSubmitBtn');
        const originalText = submitBtn.innerHTML;
        const generateQr = document.getElementById('generate_qr').checked ? '1' : '0';

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

        clearFormErrors('addAttendanceForm');
        const formData = new FormData(form);
        formData.set('generate_qr', generateQr);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                }).then(() => {
                    window.location.reload();
                });
                closeAddModal();
            } else if (data.errors) {
                showFormErrors('addAttendanceForm', data.errors);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terdapat kesalahan pada input data.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat menambahkan data.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menambahkan data.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    // Edit form submission
    const editForm = document.getElementById('editAttendanceForm');
    if (editForm) {
        editForm.removeEventListener('submit', handleEditFormSubmit); // Pastikan tidak ada listener ganda
        editForm.addEventListener('submit', handleEditFormSubmit);
    }

    function handleEditFormSubmit(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = document.getElementById('editSubmitBtn');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memperbarui...
        `;

        clearFormErrors('editAttendanceForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'PUT',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                }).then(() => {
                    window.location.reload();
                });
                closeEditModal();
            } else if (data.errors) {
                showFormErrors('editAttendanceForm', data.errors);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terdapat kesalahan pada input data.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat memperbarui data.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memperbarui data.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    // Open add modal with animation
    window.openAddModal = function() {
        clearFormErrors('addAttendanceForm');
        document.getElementById('addAttendanceForm').reset();
        document.getElementById('type').value = 'activity';
        document.getElementById('attendance_type_container').classList.remove('hidden');
        document.getElementById('generate_qr_container').classList.remove('hidden');
        const modal = document.getElementById('addAttendanceModal');
        const modalContainer = document.getElementById('addModalContainer');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContainer.classList.remove('scale-95', 'opacity-0');
            modalContainer.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    };

    // Close add modal with animation
    window.closeAddModal = function() {
        const modal = document.getElementById('addAttendanceModal');
        const modalContainer = document.getElementById('addModalContainer');
        modalContainer.classList.remove('scale-100', 'opacity-100');
        modalContainer.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    };

    // Open edit modal with animation
    window.openEditModal = function(id, type) {
        document.getElementById('editModalLoader').classList.remove('hidden');
        document.getElementById('editModalContent').classList.add('hidden');
        const modal = document.getElementById('editAttendanceModal');
        const modalContainer = document.getElementById('editModalContainer');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContainer.classList.remove('scale-95', 'opacity-0');
            modalContainer.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';

        const form = document.getElementById('editAttendanceForm');
        form.action = "{{ route('admin.attendance.update.type', ['id' => ':id', 'type' => ':type']) }}".replace(':id', id).replace(':type', type);
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_type').value = type;

        const attendanceTypeContainer = document.getElementById('edit_attendance_type_container');
        const qrSection = document.getElementById('qr_code_section');
        attendanceTypeContainer.classList.toggle('hidden', type === 'meeting');
        qrSection.classList.remove('hidden');

        fetch("{{ route('admin.attendance.edit.type', ['id' => ':id', 'type' => ':type']) }}".replace(':id', id).replace(':type', type), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            document.getElementById('editModalLoader').classList.add('hidden');
            document.getElementById('editModalContent').classList.remove('hidden');

            if (result.success) {
                const data = result.data;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_location').value = data.location;

                // Split datetime into date and time
                const startDateTime = new Date(data.start_time);
                const endDateTime = new Date(data.end_time);
                document.getElementById('edit_event_date').value = formatDateForInput(startDateTime);
                document.getElementById('edit_start_time').value = formatTimeForInput(startDateTime);
                document.getElementById('edit_end_time').value = formatTimeForInput(endDateTime);

                if (type === 'activity' && data.attendance_type_id) {
                    document.getElementById('edit_attendance_type_id').value = data.attendance_type_id;
                }

                if (type === 'activity') {
                    const qrStatus = document.getElementById('qr_code_status');
                    if (data.qr_code) {
                        qrStatus.innerHTML = `
                            <div class="flex items-center text-green-600 mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>QR Code telah dibuat</span>
                            </div>
                            <p class="text-xs text-gray-500">Berlaku hingga: ${new Date(data.qr_code.expiry_time).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                        `;
                    } else {
                        qrStatus.innerHTML = `
                            <div class="flex items-center text-yellow-600 mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>QR Code belum dibuat</span>
                            </div>
                        `;
                    }
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: result.message || 'Gagal memuat data.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
                closeEditModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat data.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5'
            });
            closeEditModal();
        });
    };

    // Close edit modal with animation
    window.closeEditModal = function() {
        const modal = document.getElementById('editAttendanceModal');
        const modalContainer = document.getElementById('editModalContainer');
        modalContainer.classList.remove('scale-100', 'opacity-100');
        modalContainer.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    };

    // View QR code dengan parameter type
    window.viewQrCode = function(id, type = 'activity') {
        const url = "{{ route('admin.attendance.qrcode', ['id' => ':id']) }}".replace(':id', id) + `?type=${type}`;
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('qrCodeImage').src = data.data.qr_image_url;
                document.getElementById('qrCodeExpiry').textContent = `Berlaku hingga: ${data.data.expiry_time}`;
                const modal = document.getElementById('qrCodeModal');
                const modalContainer = document.getElementById('qrModalContainer');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContainer.classList.remove('scale-95', 'opacity-0');
                    modalContainer.classList.add('scale-100', 'opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'QR Code Belum Tersedia',
                    text: data.message || 'QR Code belum dibuat. Silakan edit kegiatan dan centang opsi "Generate QR Code".',
                    confirmButtonColor: '#4f46e5'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat QR code.',
                confirmButtonColor: '#4f46e5'
            });
        });
    };

    // Close QR code modal
    window.closeQrCodeModal = function() {
        const modal = document.getElementById('qrCodeModal');
        const modalContainer = document.getElementById('qrModalContainer');
        modalContainer.classList.remove('scale-100', 'opacity-100');
        modalContainer.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    };

    // Toggle activity-specific fields
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            const isActivity = this.value === 'activity';
            document.getElementById('attendance_type_container').classList.toggle('hidden', !isActivity);
        });
    }

    const regenerateQrCheckbox = document.getElementById('regenerate_qr');
    if (regenerateQrCheckbox) {
        regenerateQrCheckbox.addEventListener('change', function() {
            if (!this.checked) return;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin membuat ulang QR Code? QR Code lama akan tidak berlaku.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Buat Ulang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const id = document.getElementById('edit_id').value;
                    const type = document.getElementById('edit_type').value;
                    fetch(`/admin/attendance/${id}/regenerate-qr`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const qrStatus = document.getElementById('qr_code_status');
                            qrStatus.innerHTML = `
                                <div class="flex items-center text-green-600 mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>QR Code berhasil dibuat ulang</span>
                                </div>
                                <p class="text-xs text-gray-500">Berlaku hingga: ${data.qr_code.expiry_time}</p>
                            `;
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563eb'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Gagal membuat ulang QR code.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563eb'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat membuat ulang QR code.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563eb'
                        });
                    });
                } else {
                    this.checked = false;
                }
            });
        });
    }

    // Format date for input (YYYY-MM-DD)
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Format time for input (HH:mm)
    function formatTimeForInput(date) {
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    // Clear form errors
    function clearFormErrors(formId) {
        const form = document.getElementById(formId);
        const errorElements = form.querySelectorAll('[id$="_error"]');
        errorElements.forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        const invalidInputs = form.querySelectorAll('.border-red-500');
        invalidInputs.forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-200');
        });
    }

    // Show form errors
    function showFormErrors(formId, errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${formId === 'addAttendanceForm' ? '' : 'edit_'}${field}_error`);
            const inputElement = document.getElementById(`${formId === 'addAttendanceForm' ? '' : 'edit_'}${field}`);
            if (errorElement && inputElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
                inputElement.classList.add('border-red-500');
                inputElement.classList.remove('border-gray-200');
            }
        });
    }

    // Close modals when clicking outside
    document.getElementById('addAttendanceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddModal();
        }
    });
    document.getElementById('editAttendanceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
    document.getElementById('qrCodeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQrCodeModal();
        }
    });

    // Handle session messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#2563eb'
        });
    @endif
});
</script>
@endpush
@endsection