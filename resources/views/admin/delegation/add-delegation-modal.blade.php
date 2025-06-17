<!-- Add Delegation Modal -->
<div id="addDelegationModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-auto transform transition-all duration-300 scale-95 opacity-0" id="addDelegationModalContainer">
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-2xl">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pergantian Piket
            </h3>
            <button type="button" class="text-white hover:text-gray-200 p-1 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200" onclick="closeAddDelegationModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="addDelegationForm" action="{{ route('admin.delegation.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pemohon -->
                <div class="relative">
                    <label for="requester_id" class="block text-sm font-medium text-gray-700 mb-1">Pemohon</label>
                    <select id="requester_id" name="requester_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Pilih Pemohon</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <p id="requester_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <!-- Pengganti -->
                <div class="relative">
                    <label for="delegate_id" class="block text-sm font-medium text-gray-700 mb-1">Pengganti</label>
                    <select id="delegate_id" name="delegate_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Pilih Pengganti</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <p id="delegate_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <!-- Jadwal Piket -->
                <div class="relative">
                    <label for="duty_schedule_id" class="block text-sm font-medium text-gray-700 mb-1">Jadwal Piket</label>
                    <select id="duty_schedule_id" name="duty_schedule_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Pilih Jadwal</option>
                        @foreach($dutySchedules as $ds)
                            <option value="{{ $ds->id }}">{{ $ds->day_of_week }} ({{ \Carbon\Carbon::parse($ds->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($ds->end_time)->format('H:i') }})</option>
                        @endforeach
                    </select>
                    <p id="duty_schedule_id_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <!-- Tanggal Pergantian -->
                <div class="relative">
                    <label for="delegation_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pergantian</label>
                    <input type="date" id="delegation_date" name="delegation_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <p id="delegation_date_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
            </div>
            <!-- Alasan -->
            <div class="relative">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                <textarea id="reason" name="reason" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                <p id="reason_error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <div class="flex justify-end space-x-3 pt-0 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeAddDelegationModal()" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </button>
                <button type="submit" id="addDelegationSubmitBtn" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-medium flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>