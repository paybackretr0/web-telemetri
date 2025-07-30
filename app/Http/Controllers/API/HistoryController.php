<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Menampilkan semua riwayat kehadiran pengguna (aktivitas dan piket)
     * dengan filter dan pagination yang efisien.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Attendance::with([
                'activity.attendanceType',
            ])
            ->where('user_id', $user->id);

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereHas('activity', function ($subq) use ($startDate, $endDate) {
                    $subq->whereDate('start_time', '>=', $startDate)
                         ->whereDate('start_time', '<=', $endDate);
                })
                ->orWhere(function ($subq) use ($startDate, $endDate) {
                    $subq->whereNull('activity_id')
                         ->whereBetween('check_in_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                });
            });
        }

        $query->leftJoin('activities', 'attendances.activity_id', '=', 'activities.id')
              ->select('attendances.*', DB::raw('COALESCE(activities.start_time, attendances.check_in_time) as event_date'))
              ->orderBy('event_date', 'desc');

        $history = $query->paginate(10);

        $history->getCollection()->transform(function ($item) {
            $isActivity = !is_null($item->activity_id) && $item->activity;

            if ($isActivity) {
                $type = 'activity';
                $title = $item->activity->title;
                $date = $item->activity->start_time->format('Y-m-d');
                $activityType = $item->activity->attendanceType->name ?? 'Kegiatan';
            } else { 
                $type = 'duty';
                $title = 'Piket Jaga';
                $date = $item->check_in_time->format('Y-m-d');
                $activityType = 'Piket';
            }

            return [
                'id' => $item->id,
                'type' => $type,
                'title' => $title,
                'date' => $date,
                'status' => $item->status,
                'check_in_time' => optional($item->check_in_time)->toIso8601String(),
                'check_out_time' => optional($item->check_out_time)->toIso8601String(),
                'activity_type' => $activityType,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $history,
            'message' => 'Semua riwayat kehadiran berhasil diambil'
        ]);
    }
}