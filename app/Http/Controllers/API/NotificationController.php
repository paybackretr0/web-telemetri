<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id);
        
        // Filter by read status if requested
        if ($request->has('is_read')) {
            $isRead = filter_var($request->is_read, FILTER_VALIDATE_BOOLEAN);
            $query->where('is_read', $isRead);
        }
        
        // Order by created_at in descending order (newest first)
        $query->orderBy('created_at', 'desc');
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $notifications = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }
    
    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
        
        $notification->is_read = true;
        $notification->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' => $notification,
        ]);
    }
    
    /**
     * Mark all notifications as read for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }
    
    /**
     * Get unread notification count for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Delete a specific notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }
    
    /**
     * Delete all notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)->delete();
            
        return response()->json([
            'success' => true,
            'message' => 'All notifications deleted successfully',
        ]);
    }
}