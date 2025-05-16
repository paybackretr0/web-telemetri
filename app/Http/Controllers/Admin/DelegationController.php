<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use Illuminate\Http\Request;

class DelegationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $delegations = Delegation::with(['requester', 'delegate', 'dutySchedule', 'approver'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('requester', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('delegate', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(6);

        return view('admin.delegation.index', compact('delegations', 'search'));
    }
}