<div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold text-blue-700">Tambah Pengurus Baru</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeAddUserModal()">&times;</button>
        </div>
        <form action="{{ route('pengguna.store') }}" method="POST">
            @csrf
            <div class="mt-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror" required>
                    <option value="">Pilih Role</option>
                    <option value="pengurus">Pengurus</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="divisi" class="block text-sm font-medium text-gray-700">Divisi (Opsional)</label>
                <input type="text" name="divisi" id="divisi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('divisi') border-red-500 @enderror">
                @error('divisi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="sub_divisi" class="block text-sm font-medium text-gray-700">Sub Divisi (Opsional)</label>
                <input type="text" name="sub_divisi" id="sub_divisi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('sub_divisi') border-red-500 @enderror">
                @error('sub_divisi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Tambah</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAddUserModal() {
        document.getElementById('addUserModal').classList.remove('hidden');
    }

    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
        // Clear form fields on close
        document.querySelector('#addUserModal form').reset();
    }
</script>