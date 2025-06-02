<!-- Modal Edit QR Code -->
<div id="editQrDutyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Edit QR Code Piket</h3>
        <form id="editQrDutyForm" action="#" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_qr_id" name="qr_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Masa Berlaku</label>
                <select name="expiry_time" id="edit_expiry_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="1">1 Jam</option>
                    <option value="2">2 Jam</option>
                    <option value="4">4 Jam</option>
                    <option value="8">8 Jam</option>
                    <option value="24">24 Jam</option>
                </select>
                <p id="edit_expiry_time_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Perbarui QR Code
                </button>
            </div>
        </form>
    </div>
</div>