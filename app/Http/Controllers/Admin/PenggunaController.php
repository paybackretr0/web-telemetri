<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', '!=', 'admin')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();
            
        return view('admin.users.index', [
            'title' => 'Pengguna',
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:pengurus,admin'],
            'jabatan' => ['nullable', 'string', 'max:255'], 
            'divisi' => ['nullable', 'string', 'max:255'],
            'sub_divisi' => ['nullable', 'string', 'max:255'],
        ]);

        // Add default password for new users
        $validated['password'] = Hash::make('password123'); // You might want to generate a random password

        User::create($validated);

        return response()->json(['success' => true, 'message' => 'Pengurus berhasil ditambahkan']);
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'role' => ['required', 'string', 'in:pengurus,admin'],
                'jabatan' => ['nullable', 'string', 'max:255'],
                'divisi' => ['nullable', 'string', 'max:255'],
                'sub_divisi' => ['nullable', 'string', 'max:255'],
            ]);

            $user->update($validated);

            return response()->json(['success' => true, 'message' => 'Data Pengurus berhasil diperbarui']);
                
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui data Pengurus'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengurus berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengurus'
            ], 500);
        }
    }
}