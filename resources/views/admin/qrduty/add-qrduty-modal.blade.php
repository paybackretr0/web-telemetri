<!-- Modal Create QR Code -->
<div id="addQrDutyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Buat QR Code Piket</h3>
        <form id="addQrDutyForm" action="{{ route('admin.qrduty.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Masa Berlaku</label>
                <!-- In addQrDutyModal -->
                <select name="expiry_time" id="expiry_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="2160">3 Bulan</option>
                </select>
                <p id="expiry_time_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Buat QR Code
                </button>
            </div>
        </form>
    </div>
</div>