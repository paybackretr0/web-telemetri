@extends('layouts.admin')

@section('title', 'Perizinan')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">Data Perizinan</h2>
    </div>
    
    <x-table :search="true">
        <x-slot name="header">
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Nama</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Aktivitas</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Alasan</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Disetujui Oleh</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tanggal Disetujui</th>
            <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
        </x-slot>

        @forelse ($permissions as $permission)
            <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $permission->user->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $permission->activity->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $permission->reason }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($permission->status === 'approved')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                    @elseif($permission->status === 'rejected')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permission->approver ? $permission->approver->name : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $permission->approved_at ? $permission->approved_at->format('d M Y H:i') : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <div class="flex items-center space-x-3">
                        <button onclick="showPermissionDetails({{ $permission->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group">
                            <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="group-hover:text-blue-700">Detail</span>
                        </button>
                        @if($permission->status === 'pending')
                            <button onclick="approvePermission({{ $permission->id }})" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="group-hover:text-green-700">Setujui</span>
                            </button>
                            <button onclick="rejectPermission({{ $permission->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="group-hover:text-red-700">Tolak</span>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center py-10">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-lg">Tidak ada data perizinan.</p>
                    </div>
                </td>
            </tr>
        @endforelse

        @if ($permissions->hasPages())
        <x-slot name="pagination">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if (!$permissions->onFirstPage())
                        <a href="{{ $permissions->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                            <span class="sr-only">Previous</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif
                    
                    <span class="text-sm text-gray-700">
                        Halaman {{ $permissions->currentPage() }} dari {{ $permissions->lastPage() }}
                    </span>
                    
                    @if ($permissions->hasMorePages())
                        <a href="{{ $permissions->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                            <span class="sr-only">Next</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $permissions->firstItem() ?? 0 }} - {{ $permissions->lastItem() ?? 0 }} dari {{ $permissions->total() }} data
                </div>
            </div>
        </x-slot>
        @endif
    </x-table>
</div>

<!-- Details Permission Modal -->
<div id="permissionDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 pb-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-xl">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Detail Perizinan
            </h3>
            <button class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-black hover:bg-opacity-20" onclick="closePermissionDetailsModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Loading State -->
        <div id="detailsModalLoader" class="hidden p-8 text-center">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Memuat data...</span>
            </div>
        </div>

        <!-- Modal Body -->
        <div id="detailsModalContent" class="p-6">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                    <p id="details_name" class="text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Aktivitas</label>
                    <p id="details_activity" class="text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan</label>
                    <p id="details_reason" class="text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <p id="details_status" class="text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Disetujui Oleh</label>
                    <p id="details_approver" class="text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Disetujui</label>
                    <p id="details_approved_at" class="text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lampiran</label>
                    <p id="details_attachment" class="text-gray-900"></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="closePermissionDetailsModal()" 
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Approve Permission
    window.approvePermission = function(permissionId) {
        Swal.fire({
            title: 'Konfirmasi Setujui',
            text: 'Apakah Anda yakin ingin menyetujui perizinan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/permission/${permissionId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                            text: data.message || 'Terjadi kesalahan saat menyetujui perizinan',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error approving permission:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyetujui perizinan',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
                    });
                });
            }
        });
    };

    // Reject Permission
    window.rejectPermission = function(permissionId) {
        Swal.fire({
            title: 'Konfirmasi Tolak',
            text: 'Apakah Anda yakin ingin menolak perizinan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/permission/${permissionId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                            text: data.message || 'Terjadi kesalahan saat menolak perizinan',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error rejecting permission:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menolak perizinan',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
                    });
                });
            }
        });
    };

    // Show Permission Details
    window.showPermissionDetails = function(permissionId) {
        document.getElementById('detailsModalLoader').classList.remove('hidden');
        document.getElementById('detailsModalContent').classList.add('hidden');
        document.getElementById('permissionDetailsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling

        fetch(`/admin/permission/${permissionId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            document.getElementById('detailsModalLoader').classList.add('hidden');
            document.getElementById('detailsModalContent').classList.remove('hidden');

            if (result.success) {
                const permission = result.data;
                document.getElementById('details_name').textContent = permission.user.name;
                document.getElementById('details_activity').textContent = permission.activity.title;
                document.getElementById('details_reason').textContent = permission.reason || '-';
                document.getElementById('details_status').textContent = permission.status === 'approved' ? 'Disetujui' : permission.status === 'rejected' ? 'Ditolak' : 'Menunggu';
                document.getElementById('details_approver').textContent = permission.approver ? permission.approver.name : '-';
                document.getElementById('details_approved_at').textContent = permission.approved_at ? new Date(permission.approved_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';
                document.getElementById('details_attachment').innerHTML = permission.attachment ? `<a href="/storage/${permission.attachment}" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>` : '-';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: result.message || 'Gagal memuat data perizinan',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                });
                closePermissionDetailsModal();
            }
        })
        .catch(error => {
            console.error('Error fetching permission details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat data perizinan',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });
            closePermissionDetailsModal();
        });
    };

    // Close Permission Details Modal
    window.closePermissionDetailsModal = function() {
        document.getElementById('permissionDetailsModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restore scrolling
    };

    // Close modal when clicking outside
    document.getElementById('permissionDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePermissionDetailsModal();
        }
    });
});
</script>
@endpush
@endsection