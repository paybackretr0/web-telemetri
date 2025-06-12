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
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

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
        
        if ($request->has('requester_id')) {
            $query->where('requester_id', $request->requester_id);
        }
        
        if ($request->has('delegate_id')) {
            $query->where('delegate_id', $request->delegate_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('duty_schedule_id')) {
            $query->where('duty_schedule_id', $request->duty_schedule_id);
        }
        
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
            'delegate_id' => 'required|integer|exists:users,id',
            'duty_schedule_id' => 'required|integer|exists:duty_schedules,id',
            'delegation_date' => 'required|date',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dutySchedule = DutySchedule::find($request->duty_schedule_id);
        if (!$dutySchedule) {
            return response()->json([
                'success' => false,
                'message' => 'Duty schedule not found',
            ], 404);
        }

        $isUserAssigned = $dutySchedule->users()->where('user_id', Auth::id())->exists();
        if (!$isUserAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delegate your own duty schedules',
            ], 403);
        }

        $attributes = [
            'requester_id' => Auth::id(),
            'delegate_id' => (int) $request->delegate_id,
            'duty_schedule_id' => (int) $request->duty_schedule_id,
            'delegation_date' => $request->delegation_date,
            'reason' => (string) $request->reason,
            'status' => 'pending',
        ];

        try {
            $delegation = Delegation::create($attributes);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create delegation: ' . $e->getMessage(),
            ], 500);
        }

        try {
            $formattedDate = \Carbon\Carbon::parse($delegation->delegation_date)
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d');

            Notification::create([
                'user_id' => $request->delegate_id,
                'title' => 'Permintaan Delegasi',
                'message' => Auth::user()->name . ' meminta Anda untuk menggantikan tugasnya pada ' . $formattedDate,
                'type' => 'delegation',
                'reference_id' => $delegation->id,
                'is_read' => false,
            ]);

            $delegate = User::find($request->delegate_id);
            if ($delegate->device_token) {
                $messaging = app(Messaging::class);
                $message = CloudMessage::withTarget('token', $delegate->device_token)
                    ->withNotification(FirebaseNotification::create(
                        'Permintaan Delegasi',
                        Auth::user()->name . ' meminta Anda untuk menggantikan tugasnya pada ' . $formattedDate
                    ))
                    ->withData(['delegation_id' => (string) $delegation->id]);
                $messaging->send($message);
            } else {
            }

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Permintaan Delegasi Baru',
                    'message' => Auth::user()->name . ' meminta delegasi tugas kepada ' . $delegate->name,
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);

                if ($admin->device_token) {
                    try {
                        $message = CloudMessage::withTarget('token', $admin->device_token)
                            ->withNotification(FirebaseNotification::create(
                                'Permintaan Delegasi Baru',
                                Auth::user()->name . ' meminta delegasi tugas kepada ' . $delegate->name
                            ))
                            ->withData(['delegation_id' => (string) $delegation->id]);
                        $messaging->send($message);
                    } catch (\Exception $e) {
                        
                    }
                }
            }
        } catch (\Exception $e) {

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
        
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->requester_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
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
        
        if ($request->has('delegate_id')) {
            $delegation->delegate_id = $request->delegate_id;
        }
        
        if ($request->has('duty_schedule_id')) {
            $dutySchedule = DutySchedule::find($request->duty_schedule_id);
            if (!$dutySchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duty schedule not found',
                ], 404);
            }
            
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
        
        if ($request->has('status') && $user->role === 'admin') {
            $delegation->status = $request->status;
            
            if ($request->status !== 'pending') {
                $delegation->approved_by = $user->id;
                $delegation->approved_at = now();
                
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
        
        if ($user->id === $delegation->requester_id) {
            Notification::create([
                'user_id' => $delegation->delegate_id,
                'title' => 'Permintaan Delegasi Diperbarui',
                'message' => $user->name . ' memperbarui permintaan delegasi kepada Anda',
                'type' => 'delegation',
                'reference_id' => $delegation->id,
                'is_read' => false,
            ]);
            
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
        
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->requester_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        if (!in_array($delegation->status, ['pending', 'approved']) && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel delegation with status: ' . $delegation->status,
            ], 403);
        }
        
        $delegation->status = 'cancelled';
        $delegation->save();
        
        Notification::create([
            'user_id' => $delegation->delegate_id,
            'title' => 'Delegasi Dibatalkan',
            'message' => $user->name . ' telah membatalkan permintaan delegasi kepada Anda',
            'type' => 'delegation',
            'reference_id' => $delegation->id,
            'is_read' => false,
        ]);
        
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
        
        if ($request->has('role')) {
            if ($request->role === 'requester') {
                $query->where('requester_id', $user->id);
            } elseif ($request->role === 'delegate') {
                $query->where('delegate_id', $user->id);
            }
        }
        
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

        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $delegation->delegate_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

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

        $formattedDate = \Carbon\Carbon::parse($delegation->delegation_date)
            ->timezone('Asia/Jakarta')
            ->format('Y-m-d');

        Notification::create([
            'user_id' => $delegation->requester_id,
            'title' => 'Status Delegasi Diperbarui',
            'message' => 'Permintaan delegasi Anda pada ' . $formattedDate . ' telah ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . ' oleh ' . $user->name,
            'type' => 'delegation',
            'reference_id' => $delegation->id,
            'is_read' => false,
        ]);

        $requester = User::find($delegation->requester_id);
        if ($requester->device_token) {
            try {
                $messaging = app(Messaging::class);
                $message = CloudMessage::withTarget('token', $requester->device_token)
                    ->withNotification(FirebaseNotification::create(
                        'Status Delegasi Diperbarui',
                        'Permintaan delegasi Anda pada ' . $formattedDate . ' telah ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak') . ' oleh ' . $user->name
                    ))
                    ->withData(['delegation_id' => (string) $delegation->id]);
                $messaging->send($message);
            } catch (\Exception $e) {
                
            }
        }

        if ($user->id !== $delegation->delegate_id) {
            $delegate = User::find($delegation->delegate_id);
            if ($delegate->device_token) {
                try {
                    $message = CloudMessage::withTarget('token', $delegate->device_token)
                        ->withNotification(FirebaseNotification::create(
                            'Status Delegasi Diperbarui',
                            'Permintaan delegasi pada ' . $formattedDate . ' telah ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak')
                        ))
                        ->withData(['delegation_id' => (string) $delegation->id]);
                    $messaging->send($message);
                } catch (\Exception $e) {
                    
                }
            }
        }

        if ($user->id === $delegation->delegate_id) {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Status Delegasi Diperbarui',
                    'message' => $user->name . ' telah ' . ($request->status === 'approved' ? 'menyetujui' : 'menolak') . ' permintaan delegasi dari ' . $delegation->requester->name . ' pada ' . $formattedDate,
                    'type' => 'delegation',
                    'reference_id' => $delegation->id,
                    'is_read' => false,
                ]);

                if ($admin->device_token) {
                    try {
                        $message = CloudMessage::withTarget('token', $admin->device_token)
                            ->withNotification(FirebaseNotification::create(
                                'Status Delegasi Diperbarui',
                                $user->name . ' telah ' . ($request->status === 'approved' ? 'menyetujui' : 'menolak') . ' permintaan delegasi dari ' . $delegation->requester->name . ' pada ' . $formattedDate
                            ))
                            ->withData(['delegation_id' => (string) $delegation->id]);
                        $messaging->send($message);
                    } catch (\Exception $e) {
                        
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Delegation ' . $request->status . ' successfully',
            'data' => $delegation->load(['requester', 'delegate', 'dutySchedule', 'approver']),
        ]);
    }
}