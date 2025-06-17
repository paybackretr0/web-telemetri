@extends('layouts.admin')

@section('title', 'Pergantian Piket')

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">Data Pergantian Piket</h2>
            <button id="openAddDelegationBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pergantian Piket
            </button>
        </div>

        <x-table :search="true">
            <x-slot name="header">
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tl-lg">Pemohon</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Pengganti</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Tanggal</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Disetujui Oleh</th>
                <th scope="col" class="px-6 py-3 bg-blue-50 text-left text-xs font-medium text-blue-700 uppercase tracking-wider rounded-tr-lg">Aksi</th>
            </x-slot>

            @forelse ($delegations as $delegation)
                <tr class="bg-white border-b border-gray-100 hover:bg-blue-50/50 transition duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $delegation->requester->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $delegation->delegate->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $delegation->delegation_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($delegation->status === 'approved')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                        @elseif($delegation->status === 'rejected')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                        @elseif($delegation->status === 'cancelled')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Dibatalkan</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $delegation->approver ? $delegation->approver->name : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <div class="flex items-center space-x-3">
                            <!-- Detail -->
                            <button onclick="openDetailDelegationModal({{ $delegation->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="group-hover:text-blue-700">Detail</span>
                            </button>
                            @if($delegation->status === 'pending')
                            <!-- Edit -->
                            <button onclick="openEditDelegationModal({{ $delegation->id }})" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="group-hover:text-indigo-700">Edit</span>
                            </button>
                            <!-- Approve -->
                            <button onclick="approveDelegation({{ $delegation->id }})" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="group-hover:text-green-700">Setujui</span>
                            </button>
                            <!-- Reject -->
                            <button onclick="rejectDelegation({{ $delegation->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="group-hover:text-red-700">Tolak</span>
                            </button>
                            <!-- Cancel -->
                            <button onclick="cancelDelegation({{ $delegation->id }})" class="inline-flex items-center px-3 py-1.5 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-2 group-hover:text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="group-hover:text-yellow-700">Batalkan</span>
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
                            <p class="text-lg">Tidak ada data pergantian piket.</p>
                        </div>
                    </td>
                </tr>
            @endforelse

            @if ($delegations->hasPages())
            <x-slot name="pagination">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if (!$delegations->onFirstPage())
                            <a href="{{ $delegations->previousPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif
                        
                        <span class="text-sm text-gray-700">
                            Halaman {{ $delegations->currentPage() }} dari {{ $delegations->lastPage() }}
                        </span>
                        
                        @if ($delegations->hasMorePages())
                            <a href="{{ $delegations->nextPageUrl() }}" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $delegations->firstItem() ?? 0 }} - {{ $delegations->lastItem() ?? 0 }} dari {{ $delegations->total() }} data
                    </div>
                </div>
            </x-slot>
            @endif
        </x-table>
    </div>
    @include('admin.delegation.add-delegation-modal')
    @include('admin.delegation.edit-delegation-modal')
    @include('admin.delegation.detail-delegation-modal')
    @push('scripts')
    <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.getElementById('openAddDelegationBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.getElementById('addDelegationModal');
        const container = document.getElementById('addDelegationModalContainer');
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    });

    window.closeAddDelegationModal = function() {
        const modal = document.getElementById('addDelegationModal');
        const container = document.getElementById('addDelegationModalContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    };

    function renderOptions(list, selectedId) {
        return list.map(u => `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.name || (u.day_of_week + ' (' + u.start_time.substr(0,5) + '-' + u.end_time.substr(0,5) + ')')}</option>`).join('');
    }

    // Open Edit Modal
    window.openEditDelegationModal = function(id) {
        fetch(`/admin/delegation/${id}/edit`)
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    Swal.fire('Gagal', res.message, 'error');
                    return;
                }
                const data = res.data;
                
                document.getElementById('edit_requester_id').innerHTML = renderOptions(res.users, data.requester_id);
                document.getElementById('edit_delegate_id').innerHTML = renderOptions(res.users, data.delegate_id);
                document.getElementById('edit_duty_schedule_id').innerHTML = renderOptions(res.duty_schedules, data.duty_schedule_id);
                
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_delegation_date').value = data.delegation_date.substring(0, 10);
                document.getElementById('edit_reason').value = data.reason;
                const modal = document.getElementById('editDelegationModal');
                const container = document.getElementById('editDelegationModalContainer');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            })
            .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat memuat data.', 'error'));
    };

    window.closeEditDelegationModal = function() {
        const modal = document.getElementById('editDelegationModal');
        const container = document.getElementById('editDelegationModalContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    };

    // Submit Edit Delegation
    document.getElementById('editDelegationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_id').value;
        const formData = new FormData(this);
        fetch(`/admin/delegation/${id}`, {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        })
        .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui data.', 'error'));
    });

    // Submit Add Delegation
    document.getElementById('addDelegationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        })
        .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error'));
    });

    // Approve
    window.approveDelegation = function(id) {
        Swal.fire({
            title: 'Setujui pergantian?',
            text: 'Pergantian ini akan disetujui dan tidak dapat dibatalkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Setujui',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#ef4444',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/delegation/${id}/approve`, {
                    method: 'POST', 
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan.', 'error'));
            }
        });
    };

    // Reject
    window.rejectDelegation = function(id) {
        Swal.fire({
            title: 'Tolak pergantian?',
            text: 'Pergantian ini akan ditolak dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#ef4444',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/delegation/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({reason: result.value})
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan.', 'error'));
            }
        });
    };

    // Cancel
    window.cancelDelegation = function(id) {
        Swal.fire({
            title: 'Batalkan pergantian?',
            text: 'Pergantian ini akan dibatalkan dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#ef4444',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/delegation/${id}/cancel`, {
                    method: 'POST', 
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }})
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan.', 'error'));
            }
        });
    };

    // Open Detail Modal
    window.openDetailDelegationModal = function(id) {
        fetch(`/admin/delegation/${id}`)
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    Swal.fire('Gagal', res.message, 'error');
                    return;
                }
                const d = res.data;
                document.getElementById('detailDelegationContent').innerHTML = `
                    <dl class="divide-y divide-gray-200">
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Pemohon</dt>
                            <dd class="text-gray-900">${d.requester}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Pengganti</dt>
                            <dd class="text-gray-900">${d.delegate}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Jadwal</dt>
                            <dd class="text-gray-900">${d.duty_schedule}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Tanggal</dt>
                            <dd class="text-gray-900">${d.delegation_date}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Alasan</dt>
                            <dd class="text-gray-900">${d.reason}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Status</dt>
                            <dd class="text-gray-900">${d.status_label}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Disetujui Oleh</dt>
                            <dd class="text-gray-900">${d.approver ?? '-'}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Waktu Disetujui</dt>
                            <dd class="text-gray-900">${d.approved_at ?? '-'}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="font-medium text-gray-600">Dibuat</dt>
                            <dd class="text-gray-900">${d.created_at}</dd>
                        </div>
                    </dl>
                `;
                // Tampilkan modal
                const modal = document.getElementById('detailDelegationModal');
                const container = document.getElementById('detailDelegationModalContainer');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            })
            .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat memuat detail.', 'error'));
    };

    window.closeDetailDelegationModal = function() {
        const modal = document.getElementById('detailDelegationModal');
        const container = document.getElementById('detailDelegationModalContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    };
    </script>
    @endpush
@endsection