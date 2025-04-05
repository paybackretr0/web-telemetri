<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AttendanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class ActivityController extends Controller
{
    /**
     * Display a listing of the activities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Activity::with('attendanceType');
        
        // Filter by type if requested
        if ($request->has('type')) {
            $query->whereHas('attendanceType', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->type . '%');
            });
        }
        
        // Filter by date range if requested
        if ($request->has('start_date')) {
            $query->where('start_time', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('end_time', '<=', $request->end_date);
        }
        
        // Sorting
        $sortBy = $request->sort_by ?? 'start_time';
        $sortOrder = $request->sort_order ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->per_page ?? 15;
        $activities = $query->paginate($perPage);

        // Log the structure for debugging
        Log::info('Activities data structure', [
            'count' => $activities->count(),
            'sample' => $activities->first()
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }
    
    /**
     * Store a newly created activity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'attendance_type_id' => 'required|exists:attendance_types,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Create activity
        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'qr_code' => 'qrcodes/default.png', // Placeholder QR code
            'attendance_type_id' => $request->attendance_type_id,
            'created_by' => Auth::id(),
        ]);
        
        // Load relationships
        $activity->load('attendanceType');
        
        return response()->json([
            'success' => true,
            'message' => 'Aktivitas berhasil dibuat',
            'data' => $activity,
        ], 201);
    }
    
    /**
     * Display the specified activity.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $activity = Activity::with('attendanceType')
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $activity,
        ]);
    }
    
    /**
     * Update the specified activity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'attendance_type_id' => 'sometimes|required|exists:attendance_types,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Update activity
        $activity->update($request->only([
            'title', 
            'description', 
            'location', 
            'latitude', 
            'longitude', 
            'start_time', 
            'end_time', 
            'attendance_type_id'
        ]));
        
        // Reload relationships
        $activity->load('attendanceType');
        
        return response()->json([
            'success' => true,
            'message' => 'Aktivitas berhasil diperbarui',
            'data' => $activity,
        ]);
    }
    
    /**
     * Remove the specified activity from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // Check if activity has attendances
        if ($activity->attendances()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus aktivitas yang sudah memiliki absensi',
            ], 400);
        }
        
        // Delete activity
        $activity->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Aktivitas berhasil dihapus',
        ]);
    }
    
    /**
     * Get all attendance types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceTypes()
    {
        $types = AttendanceType::all();
        
        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }
}