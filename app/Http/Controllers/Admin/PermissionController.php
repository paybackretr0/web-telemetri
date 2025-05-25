<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $permissions = Permission::with(['user', 'activity', 'approver'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('activity', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(6);

        return view('admin.permission.index', compact('permissions', 'search'));
    }

    public function approve(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            if ($permission->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Izin sudah diproses sebelumnya'
                ], 400);
            }

            $permission->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui izin'
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            if ($permission->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Izin sudah diproses sebelumnya'
                ], 400);
            }

            $permission->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak izin'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $permission = Permission::with(['user', 'activity', 'approver'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Izin tidak ditemukan'
            ], 404);
        }
    }
}