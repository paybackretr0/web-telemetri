<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QrCode;
use App\Models\Activity;
use App\Models\Meeting;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scanQrCode(Request $request, $code)
    {
        try {
            $qrcode = QrCode::where('code', $code)->firstOrFail();

            if ($qrcode->expiry_time < Carbon::now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code telah kadaluarsa.'
                ], 400);
            }

            $userId = auth()->id();
            $attendance = Attendance::where('user_id', $userId)
                ->where('check_in_time', '>=', Carbon::today())
                ->where('check_in_time', '<=', Carbon::today()->endOfDay())
                ->whereNull('check_out_time')
                ->when($qrcode->type === 'activity', function ($query) use ($qrcode) {
                    return $query->where('activity_id', $qrcode->activity_id);
                })
                ->when($qrcode->type === 'meeting', function ($query) use ($qrcode) {
                    return $query->where('meeting_id', $qrcode->meeting_id);
                })
                ->when($qrcode->type === 'duty', function ($query) {
                    return $query->whereNull('activity_id')->whereNull('meeting_id');
                })
                ->first();

            $attendanceData = [
                'user_id' => $userId,
                'status' => 'hadir',
                'check_in_time' => Carbon::now(),
                'check_in_location' => $request->input('location', 'Unknown'),
                'check_in_latitude' => $request->input('latitude'),
                'check_in_longitude' => $request->input('longitude'),
                'verified_by' => $qrcode->created_by,
            ];

            // Add type-specific fields
            if ($qrcode->type === 'activity') {
                $activity = Activity::find($qrcode->activity_id);
                if (!$activity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kegiatan tidak ditemukan.'
                    ], 404);
                }
                $attendanceData['activity_id'] = $qrcode->activity_id;
            } elseif ($qrcode->type === 'meeting') {
                $meeting = Meeting::find($qrcode->meeting_id);
                if (!$meeting) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Rapat tidak ditemukan.'
                    ], 404);
                }
                $attendanceData['meeting_id'] = $qrcode->meeting_id;
            }

            if (!$attendance) {
                // Handle check-in
                $attendance = Attendance::create($attendanceData);

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in berhasil.',
                    'data' => $attendance
                ]);
            } else {
                // For activity or meeting, prevent re-scan with message
                if ($qrcode->type === 'activity' || $qrcode->type === 'meeting') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sudah Ambil Presensi.',
                    ], 400);
                }

                // For duty type, proceed with check-out logic
                // Check if 2 hours have passed since check-in
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $hoursDifference = Carbon::now()->diffInHours($checkInTime);

                if ($hoursDifference < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Check-out tidak dapat dilakukan. Harus menunggu minimal 2 jam setelah check-in.'
                    ], 400);
                }

                // Handle check-out
                $attendance->update([
                    'check_out_time' => Carbon::now(),
                    'check_out_location' => $request->input('location', 'Unknown'),
                    'check_out_latitude' => $request->input('latitude'),
                    'check_out_longitude' => $request->input('longitude')
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Check-out berhasil.',
                    'data' => $attendance
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error scanning QR: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}