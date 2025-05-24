<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold text-blue-700">Edit Pengurus</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeEditUserModal()">&times;</button>
        </div>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="mt-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="edit_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="edit_email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="edit_role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="edit_role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror" required>
                    <option value="pengurus">Pengurus</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="edit_divisi" class="block text-sm font-medium text-gray-700">Divisi (Opsional)</label>
                <input type="text" name="divisi" id="edit_divisi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('divisi') border-red-500 @enderror">
                @error('divisi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label for="edit_sub_divisi" class="block text-sm font-medium text-gray-700">Sub Divisi (Opsional)</label>
                <input type="text" name="sub_divisi" id="edit_sub_divisi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('sub_divisi') border-red-500 @enderror">
                @error('sub_divisi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showEditUserModal() {
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
        // Clear error messages if any on close
        const errorElements = document.querySelectorAll('#editUserModal .text-red-500');
        errorElements.forEach(el => el.remove());
        const invalidInputs = document.querySelectorAll('#editUserModal .border-red-500');
        invalidInputs.forEach(el => el.classList.remove('border-red-500'));
    }

    function editUser(userId) {
        fetch(`/admin/pengguna/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_user_id').value = data.id;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_divisi').value = data.divisi || '';
                document.getElementById('edit_sub_divisi').value = data.sub_divisi || '';
                document.getElementById('editUserForm').action = `/admin/pengguna/${data.id}`;
                showEditUserModal();
            })
            .catch(error => console.error('Error fetching user data:', error));
    }
</script>