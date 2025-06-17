<!-- Edit Delegation Modal -->
<div id="editDelegationModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="editDelegationModalContainer">
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t-xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Pergantian Piket
            </h3>
            <button type="button" class="text-white hover:bg-white/10 p-2 rounded-full transition-colors" onclick="closeEditDelegationModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editDelegationForm" method="POST" action="" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pemohon -->
                <div class="relative">
                    <label for="edit_requester_id" class="block text-sm font-medium text-gray-700 mb-1">Pemohon</label>
                    <select id="edit_requester_id" name="requester_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                        <!-- options diisi via JS -->
                    </select>
                </div>
                <!-- Pengganti -->
                <div class="relative">
                    <label for="edit_delegate_id" class="block text-sm font-medium text-gray-700 mb-1">Pengganti</label>
                    <select id="edit_delegate_id" name="delegate_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                        <!-- options diisi via JS -->
                    </select>
                </div>
                <!-- Jadwal Piket -->
                <div class="relative">
                    <label for="edit_duty_schedule_id" class="block text-sm font-medium text-gray-700 mb-1">Jadwal Piket</label>
                    <select id="edit_duty_schedule_id" name="duty_schedule_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                        <!-- options diisi via JS -->
                    </select>
                </div>
                <!-- Tanggal Pergantian -->
                <div class="relative">
                    <label for="edit_delegation_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pergantian</label>
                    <input type="date" id="edit_delegation_date" name="delegation_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required>
                </div>
            </div>
            <!-- Alasan -->
            <div class="relative">
                <label for="edit_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                <textarea id="edit_reason" name="reason" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer transition-all" required></textarea>
            </div>
            <div class="flex justify-end gap-3 p-6 pt-0 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeEditDelegationModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="editDelegationSubmitBtn" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition-all font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>