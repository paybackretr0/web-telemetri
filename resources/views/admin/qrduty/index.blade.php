@extends('layouts.admin')

@section('title', 'QR Code Piket')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600 mb-4 md:mb-0">QR Code Piket</h2>
        <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center">
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

<!-- Modal Create QR Code -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Buat QR Code Piket</h3>
        <form id="qrForm" action="#" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Masa Berlaku</label>
                <select name="expiry_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="1">1 Jam</option>
                    <option value="2">2 Jam</option>
                    <option value="4">4 Jam</option>
                    <option value="8">8 Jam</option>
                    <option value="24">24 Jam</option>
                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Buat QR Code
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Show QR Code -->
<div id="showQRModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
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
<script>
function openModal() {
    document.getElementById('qrModal').classList.remove('hidden');
    document.getElementById('qrModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('qrModal').classList.add('hidden');
    document.getElementById('qrModal').classList.remove('flex');
}

function showQR(code) {
    // Implement QR code display logic here
    document.getElementById('showQRModal').classList.remove('hidden');
    document.getElementById('showQRModal').classList.add('flex');
    // You'll need to add QR code generation library and logic here
}

function closeShowQRModal() {
    document.getElementById('showQRModal').classList.add('hidden');
    document.getElementById('showQRModal').classList.remove('flex');
}

function deleteQR(id) {
    if (confirm('Apakah Anda yakin ingin menghapus QR Code ini?')) {
        // Implement delete logic here
    }
}
</script>
@endpush