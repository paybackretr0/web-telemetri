<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Activity;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Permission::with(['user', 'activity', 'approver']);
        
        // Filter by user if requested
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by status if requested
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by activity if requested
        if ($request->has('activity_id')) {
            $query->where('activity_id', $request->activity_id);
        }
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $permissions = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $permissions,
        ]);
    }
    
    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('permissions', 'public');
        }
        
        $permission = Permission::create([
            'user_id' => $request->user()->id,
            'activity_id' => $request->activity_id,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'pending', 
        ]);
        
        $admins = User::where('role', 'admin')->get();
        $currentUser = $request->user();
        $activity = Activity::find($request->activity_id);
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Permohonan Izin',
                'message' => "{$currentUser->name} mengirimkan permohonan izin tidak hadir pada {$activity->name}",
                'type' => 'permission',
                'reference_id' => $permission->id,
                'is_read' => false,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Permission request created successfully',
            'data' => $permission->load(['user', 'activity']),
        ], 201);
    }
    
    /**
     * Display the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $permission = Permission::with(['user', 'activity', 'approver'])->find($id);
        
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found',
            ], 404);
        }
        
        // Check if user is authorized to view this permission
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $permission->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $permission,
        ]);
    }
    
    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found',
            ], 404);
        }
        
        // Check if user is authorized to update this permission
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $permission->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Only allow updates if status is pending
        if ($permission->status !== 'pending' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update permission that has been processed',
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'activity_id' => 'sometimes|required|exists:activities,id',
            'reason' => 'sometimes|required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'sometimes|required|in:pending,approved,rejected',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Handle file upload if attachment exists
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($permission->attachment) {
                Storage::disk('public')->delete($permission->attachment);
            }
            
            $file = $request->file('attachment');
            $attachmentPath = $file->store('permissions', 'public');
            $permission->attachment = $attachmentPath;
        }
        
        // Update fields
        if ($request->has('activity_id')) {
            $permission->activity_id = $request->activity_id;
        }
        
        if ($request->has('reason')) {
            $permission->reason = $request->reason;
        }
        
        // Only admin can update status
        if ($request->has('status') && $user->role === 'admin') {
            $permission->status = $request->status;
            
            // If approving or rejecting, set approver info
            if ($request->status !== 'pending') {
                $permission->approved_by = $user->id;
                $permission->approved_at = now();
            }
        }

        $admins = User::where('role', 'admin')->get();
        $currentUser = $request->user();
       
        $activity = Activity::find($permission->activity_id);
        
        if ($activity) {
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Permohonan Izin',
                    'message' => "{$currentUser->name} memperbarui permohonan izin tidak hadir pada {$activity->name}",
                    'type' => 'permission',
                    'reference_id' => $permission->id,
                    'is_read' => false,
                ]);
            }
        }
        
        $permission->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission->load(['user', 'activity', 'approver']),
        ]);
    }
    
    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
    
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found',
            ], 404);
        }
    
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $permission->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        if ($user->role !== 'admin') {
            if ($permission->status === 'pending' || $permission->status === 'approved') {
                
            } 
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel permission with status: ' . $permission->status,
                ], 403);
            }
        }            
    
        $permission->status = 'cancelled';
        $permission->save();
    
        $admins = User::where('role', 'admin')->get();
        $currentUser = Auth::user();
        $activity = Activity::find($permission->activity_id);
    
        if ($activity) {
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Permohonan Izin Dibatalkan',
                    'message' => "{$currentUser->name} membatalkan permohonan izin pada {$activity->name}",
                    'type' => 'permission',
                    'reference_id' => $permission->id,
                    'is_read' => false,
                ]);
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Membatalkan Permohonan Izin',
            'data' => $permission->load(['user', 'activity', 'approver']),
        ]);
    }
    
    /**
     * Get permissions for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myPermissions(Request $request)
    {
        $user = Auth::user();
        $query = Permission::with(['activity', 'approver'])
            ->where('user_id', $user->id);
        
        // Filter by status if requested
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $permissions = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $permissions,
        ]);
    }
}