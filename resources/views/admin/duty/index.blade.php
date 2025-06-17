@php
$artifactId = 'c7f2a4d5-8e3f-4c9b-a8e2-9f7b3c2e8d1a';
@endphp
@extends('layouts.admin')

@section('title', 'Jadwal Piket')

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">Jadwal Piket Pengurus</h2>
            <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Jadwal
            </button>
        </div>

        <x-table :search="true">
            <x-slot name="header">
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Hari</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Waktu</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Lokasi</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Petugas</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
            </x-slot>

            @forelse ($schedules as $schedule)
                <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $schedule->day_of_week }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $schedule->location }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="space-y-1">
                            @foreach($schedule->users as $user)
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-400">
                                        ({{ \Carbon\Carbon::parse($user->pivot->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($user->pivot->end_date)->format('d M Y') }})
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-3">
                            <button onclick="openEditModal({{ $schedule->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="group-hover:text-blue-700">Edit</span>
                            </button>
                            <button onclick="deleteSchedule({{ $schedule->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-150 group">
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
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg">Tidak ada jadwal piket yang tersedia.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            @if ($schedules->hasPages())
            <x-slot name="pagination">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if (!$schedules->onFirstPage())
                            <a href="{{ $schedules->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif
                        
                        <span class="text-sm text-gray-700">
                            Halaman {{ $schedules->currentPage() }} dari {{ $schedules->lastPage() }}
                        </span>
                        
                        @if ($schedules->hasMorePages())
                            <a href="{{ $schedules->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }} dari {{ $schedules->total() }} data
                    </div>
                </div>
            </x-slot>
        @endif
        </x-table>
    </div>

    @include('admin.duty.add-duty-modal', ['users' => App\Models\User::all()])
    @include('admin.duty.edit-duty-modal', ['users' => App\Models\User::all()])
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let userIndex = 1;
    
        // Add form submission
        const addForm = document.getElementById('addDutyForm');
        if (addForm) {
            addForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const submitBtn = document.getElementById('addSubmitBtn');
                const originalText = submitBtn.innerHTML;
    
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                `;
    
                clearFormErrors('addDutyForm');
                const formData = new FormData(addForm);
    
                fetch(addForm.action, {
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
                        showFormErrors('addDutyForm', data.errors);
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
                            text: data.message || 'Terjadi kesalahan saat menambahkan jadwal.',
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
                        text: 'Terjadi kesalahan saat menambahkan jadwal.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    
        // Edit form submission
        const editForm = document.getElementById('editDutyForm');
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();
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
    
                clearFormErrors('editDutyForm');
                const formData = new FormData(editForm);
    
                fetch(editForm.action, {
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
                        showFormErrors('editDutyForm', data.errors);
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
                            text: data.message || 'Terjadi kesalahan saat memperbarui jadwal.',
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
                        text: 'Terjadi kesalahan saat memperbarui jadwal.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    
        // Delete schedule
        window.deleteSchedule = function (id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus jadwal piket ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('duty.destroy', ':id') }}".replace(':id', id), {
                        method: 'DELETE',
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
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat menghapus jadwal.',
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
                            text: 'Terjadi kesalahan saat menghapus jadwal.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563eb'
                        });
                    });
                }
            });
        };

        // Open add modal
        window.openAddModal = function () {
            clearFormErrors('addDutyForm');
            addForm.reset();
            document.getElementById('users_container').innerHTML = `
                <div class="user_entry border p-4 rounded-lg bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select name="users[0][id]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" disabled selected>Pilih Petugas</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="users[0][start_date]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <input type="date" name="users[0][end_date]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <p class="user_error text-red-500 text-xs mt-1 hidden"></p>
                </div>
            `;
            userIndex = 1;
            const modal = document.getElementById('addDutyModal');
            const modalContainer = document.getElementById('addModalContainer');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContainer.classList.remove('scale-95', 'opacity-0');
                modalContainer.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.body.style.overflow = 'hidden';
        };
    
        // Close add modal
        window.closeAddModal = function () {
            const modal = document.getElementById('addDutyModal');
            const modalContainer = document.getElementById('addModalContainer');
            modalContainer.classList.remove('scale-100', 'opacity-100');
            modalContainer.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        };
    
        // Open edit modal
        window.openEditModal = function (id) {
            document.getElementById('editModalLoader').classList.remove('hidden');
            document.getElementById('editModalContent').classList.add('hidden');
            const modal = document.getElementById('editDutyModal');
            const modalContainer = document.getElementById('editModalContainer');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContainer.classList.remove('scale-95', 'opacity-0');
                modalContainer.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.body.style.overflow = 'hidden';
    
            const form = document.getElementById('editDutyForm');
            form.action = "{{ route('duty.update', ':id') }}".replace(':id', id);
            document.getElementById('edit_id').value = id;
    
            fetch("{{ route('duty.edit', ':id') }}".replace(':id', id), {
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
                    document.getElementById('edit_day_of_week').value = data.day_of_week;
                    document.getElementById('edit_start_time').value = data.start_time;
                    document.getElementById('edit_end_time').value = data.end_time;
                    document.getElementById('edit_location').value = data.location;
    
                    const usersContainer = document.getElementById('edit_users_container');
                    usersContainer.innerHTML = '';
                    data.users.forEach((user, index) => {
                        usersContainer.innerHTML += `
                            <div class="user_entry border p-4 rounded-lg bg-gray-50 relative">
                                <button type="button" class="absolute top-2 right-2 text-red-600 hover:text-red-700" onclick="this.parentElement.remove()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <select name="users[${index}][id]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="" disabled>Pilih Petugas</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" ${user.id == {{ $u->id }} ? 'selected' : ''}>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="users[${index}][start_date]" value="${user.start_date}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <input type="date" name="users[${index}][end_date]" value="${user.end_date}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <p class="user_error text-red-500 text-xs mt-1 hidden"></p>
                            </div>
                        `;
                    });
                    userIndex = data.users.length;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message || 'Gagal memuat data.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
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
                    confirmButtonColor: '#2563eb'
                });
                closeEditModal();
            });
        };
    
        // Close edit modal
        window.closeEditModal = function () {
            const modal = document.getElementById('editDutyModal');
            const modalContainer = document.getElementById('editModalContainer');
            modalContainer.classList.remove('scale-100', 'opacity-100');
            modalContainer.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        };
    
        // Add new user entry in add modal
        document.getElementById('addUserBtn').addEventListener('click', function () {
            const container = document.getElementById('users_container');
            container.innerHTML += `
                <div class="user_entry border p-4 rounded-lg bg-gray-50 relative">
                    <button type="button" class="absolute top-2 right-2 text-red-600 hover:text-red-700" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select name="users[${userIndex}][id]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" disabled selected>Pilih Petugas</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="users[${userIndex}][start_date]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <input type="date" name="users[${userIndex}][end_date]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <p class="user_error text-red-500 text-xs mt-1 hidden"></p>
                </div>
            `;
            userIndex++;
        });
    
        // Add new user entry in edit modal
        document.getElementById('editAddUserBtn').addEventListener('click', function () {
            const container = document.getElementById('edit_users_container');
            container.innerHTML += `
                <div class="user_entry border p-4 rounded-lg bg-gray-50 relative">
                    <button type="button" class="absolute top-2 right-2 text-red-600 hover:text-red-700" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select name="users[${userIndex}][id]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:borderiley-blue-500" required>
                            <option value="" disabled selected>Pilih Petugas</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="users[${userIndex}][start_date]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <input type="date" name="users[${userIndex}][end_date]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <p class="user_error text-red-500 text-xs mt-1 hidden"></p>
                </div>
            `;
            userIndex++;
        });
    
        // Clear form errors
        function clearFormErrors(formId) {
            const form = document.getElementById(formId);
            const errorElements = form.querySelectorAll('[id$="_error"], .user_error');
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
                if (field.startsWith('users.')) {
                    const [_, index, subField] = field.split('.');
                    const entry = document.querySelector(`#${formId} .user_entry:nth-child(${parseInt(index) + 1}) .user_error`);
                    if (entry) {
                        entry.textContent = errors[field][0];
                        entry.classList.remove('hidden');
                        const input = document.querySelector(`#${formId} .user_entry:nth-child(${parseInt(index) + 1}) [name="users[${index}][${subField}]"]`);
                        if (input) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-gray-200');
                        }
                    }
                } else {
                    const errorElement = document.getElementById(`${formId === 'addDutyForm' ? '' : 'edit_'}${field}_error`);
                    const inputElement = document.getElementById(`${formId === 'addDutyForm' ? '' : 'edit_'}${field}`);
                    if (errorElement && inputElement) {
                        errorElement.textContent = errors[field][0];
                        errorElement.classList.remove('hidden');
                        inputElement.classList.add('border-red-500');
                        inputElement.classList.remove('border-gray-200');
                    }
                }
            });
        }
    
        // Close modals when clicking outside
        document.getElementById('addDutyModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeAddModal();
            }
        });
        document.getElementById('editDutyModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
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

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });
        @endif
    });
    </script>
    @endpush
@endsection

