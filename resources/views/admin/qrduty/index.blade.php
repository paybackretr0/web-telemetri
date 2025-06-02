@extends('layouts.admin')

@section('title', 'QR Code Piket')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">QR Code Piket</h2>
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat QR Code
        </button>
    </div>

    <x-table :search="true">
        <x-slot name="header">
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Kode</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tanggal</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Dibuat Oleh</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
        </x-slot>

        @forelse ($qrcodes as $qr)
            <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $qr->code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $qr->created_at->format('d M Y H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $qr->creator->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($qr->expiry_time > now())
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Kadaluarsa</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-3">
                        <button onclick="showQR('{{ $qr->code }}')" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group">
                            <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="group-hover:text-blue-700">Lihat</span>
                        </button>
                        <button onclick="editQR('{{ $qr->id }}')" class="inline-flex items-center px-3 py-1.5 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors duration-150 group">
                            <svg class="w-4 h-4 mr-2 group-hover:text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="group-hover:text-yellow-700">Edit</span>
                        </button>
                        <button onclick="deleteQR('{{ $qr->id }}')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-150 group">
                            <svg class="w-4 h-4 mr-2 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="group-hover:text-red-700">Hapus</span>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center py-10">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-lg">Tidak ada data QR Code.</p>
                    </div>
                </td>
            </tr>
        @endforelse

        @if ($qrcodes->hasPages())
            <x-slot name="pagination">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if (!$qrcodes->onFirstPage())
                            <a href="{{ $qrcodes->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif
                        
                        <span class="text-sm text-gray-700">
                            Halaman {{ $qrcodes->currentPage() }} dari {{ $qrcodes->lastPage() }}
                        </span>
                        
                        @if ($qrcodes->hasMorePages())
                            <a href="{{ $qrcodes->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $qrcodes->firstItem() ?? 0 }} - {{ $qrcodes->lastItem() ?? 0 }} dari {{ $qrcodes->total() }} data
                    </div>
                </div>
            </x-slot>
        @endif
    </x-table>
</div>

<!-- Include modals -->
@include('admin.qrduty.add-qrduty-modal')
@include('admin.qrduty.edit-qrduty-modal')

<!-- Modal Show QR Code -->
<div id="showQRModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">QR Code Piket</h3>
        <div id="qrCodeDisplay" class="flex justify-center mb-4">
            <!-- QR Code will be displayed here -->
        </div>
        <div class="flex justify-end">
            <button onclick="closeShowQRModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add QR Code Form Submit
    const addQrDutyForm = document.getElementById('addQrDutyForm');
    if (addQrDutyForm) {
        addQrDutyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAddFormSubmit(this);
        });
    }

    // Edit QR Code Form Submit
    const editQrDutyForm = document.getElementById('editQrDutyForm');
    if (editQrDutyForm) {
        editQrDutyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleEditFormSubmit(this);
        });
    }

    // Close modals when clicking outside
    document.getElementById('addQrDutyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddModal();
        }
    });

    document.getElementById('editQrDutyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.getElementById('showQRModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeShowQRModal();
        }
    });
});

function openAddModal() {
    document.getElementById('addQrDutyModal').classList.remove('hidden');
    document.getElementById('addQrDutyModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addQrDutyModal').classList.add('hidden');
    document.getElementById('addQrDutyModal').classList.remove('flex');
    document.getElementById('addQrDutyForm').reset();
    clearFormErrors('addQrDutyForm');
}

function openEditModal() {
    document.getElementById('editQrDutyModal').classList.remove('hidden');
    document.getElementById('editQrDutyModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editQrDutyModal').classList.add('hidden');
    document.getElementById('editQrDutyModal').classList.remove('flex');
    document.getElementById('editQrDutyForm').reset();
    clearFormErrors('editQrDutyForm');
}

function showQR(code) {
    fetch(`/admin/qrduty/show/${code}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const qrCodeDisplay = document.getElementById('qrCodeDisplay');
            qrCodeDisplay.innerHTML = `
                <div class="flex flex-col items-center">
                    <img src="${data.data.qr_image_url}" alt="QR Code" class="w-64 h-64 mb-4">
                    <p class="text-sm text-gray-600">Kode: ${data.data.code}</p>
                    <p class="text-sm text-gray-600">Berlaku hingga: ${data.data.expiry_time}</p>
                </div>
            `;
            document.getElementById('showQRModal').classList.remove('hidden');
            document.getElementById('showQRModal').classList.add('flex');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat menampilkan QR Code.',
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
            text: 'Terjadi kesalahan saat menampilkan QR Code.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#2563eb'
        });
    });
}

function closeShowQRModal() {
    document.getElementById('showQRModal').classList.add('hidden');
    document.getElementById('showQRModal').classList.remove('flex');
}

function editQR(id) {
    document.getElementById('edit_qr_id').value = id;
    document.getElementById('editQrDutyForm').action = `/admin/qrduty/${id}`;
    openEditModal();
}

function deleteQR(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus QR Code ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/qrduty/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
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
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat menghapus QR Code.',
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
                    text: 'Terjadi kesalahan saat menghapus QR Code.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                });
            });
        }
    });
}

function handleAddFormSubmit(form) {
    const formData = new FormData(form);

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
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.reload();
            });
            closeAddModal();
        } else if (data.errors) {
            showFormErrors('addQrDutyForm', data.errors);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terdapat kesalahan pada input data.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat membuat QR Code.',
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
            text: 'Terjadi kesalahan saat membuat QR Code.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#2563eb'
        });
    });
}

function handleEditFormSubmit(form) {
    const formData = new FormData(form);
    formData.append('_method', 'PUT');

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
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.reload();
            });
            closeEditModal();
        } else if (data.errors) {
            showFormErrors('editQrDutyForm', data.errors);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terdapat kesalahan pada input data.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat memperbarui QR Code.',
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
            text: 'Terjadi kesalahan saat memperbarui QR Code.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#2563eb'
        });
    });
}

function showFormErrors(formId, errors) {
    clearFormErrors(formId);
    Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(`${formId === 'addQrDutyForm' ? '' : 'edit_'}${field}_error`);
        const inputElement = document.getElementById(`${formId === 'addQrDutyForm' ? '' : 'edit_'}${field}`);
        if (errorElement && inputElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove('hidden');
            inputElement.classList.add('border-red-500');
            inputElement.classList.remove('border-gray-300');
        }
    });
}

function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (form) {
        const errorElements = form.querySelectorAll('[id$="_error"]');
        const inputElements = form.querySelectorAll('input, select, textarea');
        
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });
        
        inputElements.forEach(element => {
            element.classList.remove('border-red-500');
            element.classList.add('border-gray-300');
        });
    }
}

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

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb'
    });
@endif
</script>
@endpush