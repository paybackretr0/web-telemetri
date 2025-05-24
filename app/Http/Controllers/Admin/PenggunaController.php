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
            'divisi' => ['nullable', 'string', 'max:255'],
            'sub_divisi' => ['nullable', 'string', 'max:255'],
        ]);

        User::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengurus berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $user = User::findOrFail($user->id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:pengurus,admin'],
            'divisi' => ['nullable', 'string', 'max:255'],
            'sub_divisi' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengurus berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengurus berhasil dihapus');
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }
}