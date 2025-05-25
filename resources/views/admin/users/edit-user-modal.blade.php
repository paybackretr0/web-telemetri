<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 pb-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-xl">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Pengurus
            </h3>
            <button class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-black hover:bg-opacity-20" onclick="closeEditUserModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Loading State -->
        <div id="editModalLoader" class="hidden p-8 text-center">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Memuat data...</span>
            </div>
        </div>

        <!-- Modal Body -->
        <div id="editModalContent" class="p-6">
            <form id="editUserForm" method="POST">
                <input type="hidden" name="_token" value="">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="user_id" id="edit_user_id">

                <div class="space-y-5">
                    <!-- Name Field -->
                    <div>
                        <label for="edit_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Lengkap
                            </span>
                        </label>
                        <input type="text" name="name" id="edit_name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="Masukkan nama lengkap"
                               required>
                        <div id="edit_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="edit_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email
                            </span>
                        </label>
                        <input type="email" name="email" id="edit_email" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="Masukkan alamat email"
                               required>
                        <div id="edit_email_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Role Field -->
                    <div>
                        <label for="edit_role" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                Role
                            </span>
                        </label>
                        <select name="role" id="edit_role" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" 
                                required>
                            <option value="pengurus">Pengurus</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div id="edit_role_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Divisi Field -->
                    <div>
                        <label for="edit_divisi" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Divisi
                                <span class="text-gray-400 ml-1">(Opsional)</span>
                            </span>
                        </label>
                        <input type="text" name="divisi" id="edit_divisi" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="Masukkan nama divisi">
                        <div id="edit_divisi_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Sub Divisi Field -->
                    <div>
                        <label for="edit_sub_divisi" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Sub Divisi
                                <span class="text-gray-400 ml-1">(Opsional)</span>
                            </span>
                        </label>
                        <input type="text" name="sub_divisi" id="edit_sub_divisi" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="Masukkan nama sub divisi">
                        <div id="edit_sub_divisi_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditUserModal()" 
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-medium flex items-center shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showEditUserModal() {
        document.getElementById('editUserModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restore scrolling
        clearEditFormErrors();
    }

    function clearEditFormErrors() {
        // Clear all error messages
        const errorElements = document.querySelectorAll('#editUserModal [id$="_error"]');
        errorElements.forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Remove error styling from inputs
        const invalidInputs = document.querySelectorAll('#editUserModal .border-red-500');
        invalidInputs.forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });
    }

    function showEditFormErrors(errors) {
        clearEditFormErrors();
        
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`edit_${field}_error`);
            const inputElement = document.getElementById(`edit_${field}`);
            
            if (errorElement && inputElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
                inputElement.classList.add('border-red-500');
                inputElement.classList.remove('border-gray-300');
            }
        });
    }

    function editUser(userId) {
        // Show loading state
        document.getElementById('editModalLoader').classList.remove('hidden');
        document.getElementById('editModalContent').classList.add('hidden');
        showEditUserModal();

        fetch(`/admin/pengguna/${userId}/edit`)
            .then(response => response.json())
            .then(result => {
                // Hide loading state
                document.getElementById('editModalLoader').classList.add('hidden');
                document.getElementById('editModalContent').classList.remove('hidden');

                if (result.success) {
                    const user = result.data;
                    
                    // Populate form fields
                    document.getElementById('edit_user_id').value = user.id;
                    document.getElementById('edit_name').value = user.name || '';
                    document.getElementById('edit_email').value = user.email || '';
                    document.getElementById('edit_role').value = user.role || 'pengurus';
                    document.getElementById('edit_divisi').value = user.divisi || '';
                    document.getElementById('edit_sub_divisi').value = user.sub_divisi || '';
                    
                    // Set form action
                    document.getElementById('editUserForm').action = `/admin/pengguna/${user.id}`;
                    
                    // Set CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                                || document.querySelector('input[name="_token"]')?.value;
                    if (csrfToken) {
                        document.querySelector('#editUserForm input[name="_token"]').value = csrfToken;
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message || 'Gagal memuat data user',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563eb'
                    });
                    closeEditUserModal();
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memuat data user',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                });
                closeEditUserModal();
            });
    }

    // Close modal when clicking outside
    document.getElementById('editUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditUserModal();
        }
    });

    // Handle form submission for editing user
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memperbarui...
        `;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data Pengurus berhasil diperbarui',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    window.location.reload(); // Reload to reflect changes
                });
                closeEditUserModal();
            } else if (data.errors) {
                showEditFormErrors(data.errors);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terdapat kesalahan pada input data',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat memperbarui data',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memperbarui data',
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563eb'
            });
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>