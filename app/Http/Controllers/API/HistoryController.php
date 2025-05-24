<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MeetingAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan riwayat kehadiran aktivitas pengguna
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activityHistory(Request $request)
    {
        $user = Auth::user();
        
        $attendances = Attendance::with(['activity', 'activity.attendanceType'])
            ->where('user_id', $user->id)
            ->when($request->has('start_date') && $request->has('end_date'), function($query) use ($request) {
                return $query->whereHas('activity', function($q) use ($request) {
                    $q->whereBetween('date', [$request->start_date, $request->end_date]);
                });
            })
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => $attendances,
            'message' => 'Riwayat kehadiran aktivitas berhasil diambil'
        ]);
    }
    
    /**
     * Menampilkan riwayat kehadiran rapat pengguna
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function meetingHistory(Request $request)
    {
        $user = Auth::user();
        
        $meetingAttendances = MeetingAttendance::with('meeting')
            ->where('user_id', $user->id)
            ->when($request->has('start_date') && $request->has('end_date'), function($query) use ($request) {
                return $query->whereHas('meeting', function($q) use ($request) {
                    $q->whereBetween('meeting_date', [$request->start_date, $request->end_date]);
                });
            })
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => $meetingAttendances,
            'message' => 'Riwayat kehadiran rapat berhasil diambil'
        ]);
    }
    
    /**
     * Menampilkan semua riwayat kehadiran pengguna (aktivitas dan rapat)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allHistory(Request $request)
    {
        $user = Auth::user();
        
        // Ambil riwayat aktivitas
        $activities = Attendance::with(['activity', 'activity.attendanceType'])
            ->where('user_id', $user->id)
            ->when($request->has('start_date') && $request->has('end_date'), function($query) use ($request) {
                return $query->whereHas('activity', function($q) use ($request) {
                    $q->whereBetween('date', [$request->start_date, $request->end_date]);
                });
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'activity',
                    'title' => $item->activity->title ?? 'Aktivitas',
                    'date' => $item->activity->date ?? $item->created_at->format('Y-m-d'),
                    'status' => $item->status,
                    'check_in_time' => $item->check_in_time,
                    'check_out_time' => $item->check_out_time,
                    'activity_type' => $item->activity->attendanceType->name ?? 'Umum',
                    'created_at' => $item->created_at
                ];
            });
            
        // Ambil riwayat rapat
        $meetings = MeetingAttendance::with('meeting')
            ->where('user_id', $user->id)
            ->when($request->has('start_date') && $request->has('end_date'), function($query) use ($request) {
                return $query->whereHas('meeting', function($q) use ($request) {
                    $q->whereBetween('meeting_date', [$request->start_date, $request->end_date]);
                });
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'meeting',
                    'title' => $item->meeting->title ?? 'Rapat',
                    'date' => $item->meeting->meeting_date ?? $item->created_at->format('Y-m-d'),
                    'status' => $item->status,
                    'check_in_time' => $item->check_in_time,
                    'check_out_time' => null,
                    'activity_type' => 'Rapat',
                    'created_at' => $item->created_at
                ];
            });
            
        // Gabungkan dan urutkan berdasarkan tanggal terbaru
        $allHistory = $activities->concat($meetings)
            ->sortByDesc('created_at')
            ->values();
            
        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 10;
        $total = $allHistory->count();
        
        $paginatedItems = $allHistory->forPage($page, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => (int)$page,
                'data' => $paginatedItems->values()->all(),
                'from' => (($page - 1) * $perPage) + 1,
                'last_page' => ceil($total / $perPage),
                'per_page' => $perPage,
                'to' => min($page * $perPage, $total),
                'total' => $total,
            ],
            'message' => 'Semua riwayat kehadiran berhasil diambil'
        ]);
    }
}