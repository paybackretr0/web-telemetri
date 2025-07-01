@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">Daftar Pengurus</h2>
        <button
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold cursor-pointer" 
            onclick="showAddUserModal()"
        >Tambah Pengurus</button>
    </div>

    <x-table :search="true">
        <x-slot name="header">
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Nama</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Email</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Jabatan</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Divisi</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Sub Divisi</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
        </x-slot>

        @forelse($users as $user)
            <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200"
                                src="{{ $user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=EBF4FF&color=3B82F6&font-size=0.5&bold=true' }}"
                                alt="{{ $user->name }}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $user->jabatan }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->divisi ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->sub_divisi ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <div class="flex items-center space-x-3">
                        <a onclick="editUser({{ $user->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group cursor-pointer">
                            <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="group-hover:text-blue-700">Edit</span>
                        </a>
                        <button class="delete-user-btn inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-150 group" data-user-id="{{ $user->id }}">
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
                <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center py-10">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-lg">Tidak ada data pengurus.</p>
                    </div>
                </td>
            </tr>
        @endforelse

        @if ($users->hasPages())
        <x-slot name="pagination">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if (!$users->onFirstPage())
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                            <span class="sr-only">Previous</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    <span class="text-sm text-gray-700">
                        Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
                    </span>

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                            <span class="sr-only">Next</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>

                <div class="text-sm text-gray-600">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </div>
            </div>
        </x-slot>
        @endif
    </x-table>

    {{-- Include the modals --}}
    @include('admin.users.add-user-modal')
    @include('admin.users.edit-user-modal')

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listeners for delete buttons
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus pengurus ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/pengguna/${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'X-HTTP-Method-Override': 'DELETE'
                            },
                        })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pengurus berhasil dihapus',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#2563eb'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus pengurus',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#2563eb'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting user:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus pengurus',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563eb'
                            });
                        });
                    }
                });
            });
        });

        // Display success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });
        @endif

        @if ($errors->any())
            const hasStoreErrors = {{ $errors->has('name') || $errors->has('email') || $errors->has('role') || $errors->has('divisi') || $errors->has('sub_divisi') ? 'true' : 'false' }};
            if (hasStoreErrors) {
                showAddUserModal();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terdapat kesalahan pada input data',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                @if(session('user_id_for_edit_modal'))
                    const userIdForEdit = {{ session('user_id_for_edit_modal') }};
                    editUser(userIdForEdit);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terdapat kesalahan pada input data',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
                    });
                @else
                    console.error('Validation errors occurred, but unable to determine which edit modal to re-open.');
                @endif
            }
        @endif
    });
    </script>
    @endpush
@endsection