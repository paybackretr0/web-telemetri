<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use App\Models\DutySchedule;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DelegationController extends Controller
{
    /**
     * Display a listing of the delegations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Delegation::with(['requester', 'delegate', 'dutySchedule', 'approver']);
        
        // Filter by requester if requested
        if ($request->has('requester_id')) {
            $query->where('requester_id', $request->requester_id);
        }
        
        // Filter by delegate if requested
        if ($request->has('delegate_id')) {
            $query->where('delegate_id', $request->delegate_id);
        }
        
        // Filter by status if requested
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by duty schedule if requested
        if ($request->has('duty_schedule_id')) {
            $query->where('duty_schedule_id', $request->duty_schedule_id);
        }
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $delegations = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $delegations,
        ]);
    }
    
    /**
     * Store a newly created delegation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delegate_id' => 'required|exists:users,id',
            'duty_schedule_id' => 'required|exists:duty_schedules,id',
            'delegation_date' => 'required|date',
            'reason' => 'required|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Check if the duty schedule belongs to the requester
        $dutySchedule = DutySchedule::find($request->duty_schedule_id);
        if (!$dutySchedule) {
            return response()->json([
                'success' => false,
                'message' => 'Duty schedule not found',
            ], 404);
        }
        
        // Check if the authenticated user is assigned to this duty schedule
        $isUserAssigned = $dutySchedule->users()->where('user_id', Auth::id())->exists();
        if (!$isUserAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delegate your own duty schedules',
            ], 403);
        }
        
        // Create delegation
        $delegation = Delegation::create([
            'requester_id' => Auth::id(),
            'delegate_id' => $request->delegate_id,
            'duty_schedule_id' => $request->duty_schedule_id,
            'delegation_date' => $request->delegation_date,
            'reason' => $request->reason,
            'status' => 'pending', // Default status
        ]);
        
        // Notify the delegate
        Notification::create([
            'user_id' => $request->delegate_id,
            'title' => 'Permintaan Delegasi',
            'message' => Auth::user()->name . ' meminta Anda untuk menggantikan tugasnya pada ' . $request->delegation_date,
            'type' => 'delegation',
            'reference_id' => $delegation->id,
            'is_read' => false,
        ]);
        
        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Permintaan Delegasi Baru',
                'message' => Auth::user()->name . ' meminta delegasi tugas kepada ' . User::find($request->delegate_id)->name,
                'type' => 'delegation',
                'reference_id' => $delegation->id,
                'is_read' => false,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delegation request created successfully',
            'data' => $delegation->load(['requester', 'delegate', 'dutySchedule']),
        ], 201);
    }
    
    /**
     * Display the specified delegation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $delegation = Delegation::with(['requester', 'delegate', 'dutySchedule', 'approver'])->find($id);
        
        if (!$delegation) {
            return response()->json([
                'success' => false,
                'message' => 'Delegation not found',
            ], 404);
        }
        
        // Check if user is authorized to view this delegation
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->requester_id && $user->id !== $delegation->delegate_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $delegation,
        ]);
    }
    
    /**
     * Update the specified delegation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $delegation = Delegation::find($id);
        
        if (!$delegation) {
            return response()->json([
                'success' => false,
                'message' => 'Delegation not found',
            ], 404);
        }
        
        // Check if user is authorized to update this delegation
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->requester_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Only allow updates if status is pending
        if ($delegation->status !== 'pending' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update delegation that has been processed',
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'delegate_id' => 'sometimes|required|exists:users,id',
            'duty_schedule_id' => 'sometimes|required|exists:duty_schedules,id',
            'delegation_date' => 'sometimes|required|date',
            'reason' => 'sometimes|required|string|max:500',
            'status' => 'sometimes|required|in:pending,approved,rejected',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Update fields
        if ($request->has('delegate_id')) {
            $delegation->delegate_id = $request->delegate_id;
        }
        
        if ($request->has('duty_schedule_id')) {
            // Check if the duty schedule belongs to the requester
            $dutySchedule = DutySchedule::find($request->duty_schedule_id);
            if (!$dutySchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duty schedule not found',
                ], 404);
            }
            
            // Check if the requester is assigned to this duty schedule
            $isUserAssigned = $dutySchedule->users()->where('user_id', $delegation->requester_id)->exists();
            if (!$isUserAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delegate your own duty schedules',
                ], 403);
            }
            
            $delegation->duty_schedule_id = $request->duty_schedule_id;
        }
        
        if ($request->has('delegation_date')) {
            $delegation->delegation_date = $request->delegation_date;
        }
        
        if ($request->has('reason')) {
            $delegation->reason = $request->reason;
        }
        
        // Only admin can update status
        if ($request->has('status') && $user->role === 'admin') {
            $delegation->status = $request->status;
            
            // If approving or rejecting, set approver info
            if ($request->status !== 'pending') {
                $delegation->approved_by = $user->id;
                $delegation->approved_at = now();
                
                // Notify the requester and delegate about the status change
                Notification::create([
                    'user_id' => $delegation->requester_id,
                    'title' => 'Status Delegasi Diperbarui',
                    'message' => 'Permintaan delegasi Anda telah ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak'),
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);
                
                Notification::create([
                    'user_id' => $delegation->delegate_id,
                    'title' => 'Status Delegasi Diperbarui',
                    'message' => 'Permintaan delegasi kepada Anda telah ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak'),
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);
            }
        }
        
        $delegation->save();
        
        // If the requester updates the delegation, notify the delegate and admins
        if ($user->id === $delegation->requester_id) {
            // Notify the delegate
            Notification::create([
                'user_id' => $delegation->delegate_id,
                'title' => 'Permintaan Delegasi Diperbarui',
                'message' => $user->name . ' memperbarui permintaan delegasi kepada Anda',
                'type' => 'delegation',
                'reference_id' => $delegation->id,
                'is_read' => false,
            ]);
            
            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Permintaan Delegasi Diperbarui',
                    'message' => $user->name . ' memperbarui permintaan delegasi',
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delegation updated successfully',
            'data' => $delegation->load(['requester', 'delegate', 'dutySchedule', 'approver']),
        ]);
    }
    
    /**
     * Cancel the specified delegation (instead of deleting it).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $delegation = Delegation::find($id);
        
        if (!$delegation) {
            return response()->json([
                'success' => false,
                'message' => 'Delegation not found',
            ], 404);
        }
        
        // Check if user is authorized to cancel this delegation
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->requester_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Only allow cancellation if status is pending or approved
        if (!in_array($delegation->status, ['pending', 'approved']) && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel delegation with status: ' . $delegation->status,
            ], 403);
        }
        
        // Update status to cancelled instead of deleting
        $delegation->status = 'cancelled';
        $delegation->save();
        
        // Notify the delegate
        Notification::create([
            'user_id' => $delegation->delegate_id,
            'title' => 'Delegasi Dibatalkan',
            'message' => $user->name . ' telah membatalkan permintaan delegasi kepada Anda',
            'type' => 'delegation',
            'reference_id' => $delegation->id,
            'is_read' => false,
        ]);
        
        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->id !== $user->id) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Delegasi Dibatalkan',
                    'message' => $user->name . ' telah membatalkan permintaan delegasi',
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delegation cancelled successfully',
            'data' => $delegation->load(['requester', 'delegate', 'dutySchedule', 'approver']),
        ]);
    }
    
    /**
     * Get delegations for the authenticated user (as requester or delegate).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myDelegations(Request $request)
    {
        $user = Auth::user();
        $query = Delegation::with(['requester', 'delegate', 'dutySchedule', 'approver'])
            ->where(function($q) use ($user) {
                $q->where('requester_id', $user->id)
                  ->orWhere('delegate_id', $user->id);
            });
        
        // Filter by role (requester or delegate)
        if ($request->has('role')) {
            if ($request->role === 'requester') {
                $query->where('requester_id', $user->id);
            } elseif ($request->role === 'delegate') {
                $query->where('delegate_id', $user->id);
            }
        }
        
        // Filter by status if requested
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $delegations = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $delegations,
        ]);
    }
    
    /**
     * Process a delegation request (approve or reject).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function processDelegations(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $delegation = Delegation::find($id);
        
        if (!$delegation) {
            return response()->json([
                'success' => false,
                'message' => 'Delegation not found',
            ], 404);
        }
        
        // Check if user is authorized to process this delegation
        // Allow both admin and the delegate to process
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->delegate_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Only allow processing if status is pending
        if ($delegation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot process delegation that has already been processed',
            ], 403);
        }
        
        $delegation->status = $request->status;
        $delegation->approved_by = $user->id;
        $delegation->approved_at = now();
        $delegation->save();
        
        // Notify the requester
        Notification::create([
            'user_id' => $delegation->requester_id,
            'title' => 'Status Delegasi Diperbarui',
            'message' => 'Permintaan delegasi Anda telah ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . ' oleh ' . $user->name,
            'type' => 'delegation',
            'reference_id' => $delegation->id,
            'is_read' => false,
        ]);
        
        // Notify admins if the delegate processed the request
        if ($user->id === $delegation->delegate_id) {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Status Delegasi Diperbarui',
                    'message' => $user->name . ' telah ' . ($request->status === 'approved' ? 'menyetujui' : 'menolak') . ' permintaan delegasi dari ' . $delegation->requester->name,
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delegation ' . $request->status . ' successfully',
            'data' => $delegation->load(['requester', 'delegate', 'dutySchedule', 'approver']),
        ]);
    }
}