<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

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
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(6);

        return view('admin.permission.index', compact('permissions', 'search'));
    }
}