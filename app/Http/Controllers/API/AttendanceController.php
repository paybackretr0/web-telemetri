<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QrCode;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\UserDutySchedule;
use App\Models\Delegation;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scanQrCode(Request $request, $code)
    {
        try {
            $qrcode = QrCode::where('code', $code)->firstOrFail();

            if ($qrcode->expiry_time < Carbon::now('Asia/Jakarta')) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code telah kadaluarsa.'
                ], 400);
            }

            $userId = auth()->id();
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $currentDate = Carbon::today('Asia/Jakarta');

            $dayMapping = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu',
            ];
            $currentDayOfWeek = $dayMapping[$currentDate->englishDayOfWeek] ?? $currentDate->englishDayOfWeek;

            if ($qrcode->type === 'duty') {
                if (!$latitude || !$longitude) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Koordinat lokasi diperlukan untuk absen piket.'
                    ], 400);
                }

                $allowedLocations = config('duty_locations');

                $isValidLocation = false;
                foreach ($allowedLocations as $location) {
                    $distance = $this->calculateDistance(
                        $latitude,
                        $longitude,
                        $location['latitude'],
                        $location['longitude']
                    );

                    if ($distance <= $location['radius']) {
                        $isValidLocation = true;
                        break;
                    }
                }

                if (!$isValidLocation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak berada di lokasi piket yang diizinkan.'
                    ], 400);
                }

                $isScheduled = UserDutySchedule::where('user_id', $userId)
                    ->whereHas('dutySchedule', function ($query) use ($currentDayOfWeek) {
                        $query->where('day_of_week', $currentDayOfWeek);
                    })
                    ->where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->exists();

                $isDelegated = Delegation::where('delegate_id', $userId)
                    ->where('delegation_date', $currentDate)
                    ->where('status', 'approved')
                    ->exists();

                if (!$isScheduled && !$isDelegated) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bukan jadwal piket Anda.'
                    ], 400);
                }
            }

            $attendance = Attendance::where('user_id', $userId)
                ->where('check_in_time', '>=', Carbon::today('Asia/Jakarta'))
                ->where('check_in_time', '<=', Carbon::today('Asia/Jakarta')->endOfDay())
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
                'check_in_time' => Carbon::now('Asia/Jakarta'),
                'check_in_location' => $request->input('location', 'Unknown'),
                'check_in_latitude' => $latitude,
                'check_in_longitude' => $longitude,
                'verified_by' => $qrcode->created_by,
            ];

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
                $attendance = Attendance::create($attendanceData);

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in berhasil.',
                    'data' => $attendance
                ]);
            } else {
                if ($qrcode->type === 'activity' || $qrcode->type === 'meeting') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sudah Ambil Presensi.',
                    ], 400);
                }

                $currentTime = Carbon::now('Asia/Jakarta');
                $checkInTime = Carbon::parse($attendance->check_in_time, 'Asia/Jakarta');

                $minutesDifference = $checkInTime->diffInMinutes($currentTime, false);

                if ($minutesDifference < 120) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Check-out tidak dapat dilakukan. Harus menunggu minimal 2 jam setelah check-in.'
                    ], 400);
                }

                if ($qrcode->type === 'duty') {
                    $isValidLocation = false;
                    foreach ($allowedLocations as $location) {
                        $distance = $this->calculateDistance(
                            $latitude,
                            $longitude,
                            $location['latitude'],
                            $location['longitude']
                        );

                        if ($distance <= $location['radius']) {
                            $isValidLocation = true;
                            break;
                        }
                    }

                    if (!$isValidLocation) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda tidak berada di lokasi piket yang diizinkan untuk check-out.'
                        ], 400);
                    }
                }

                $attendance->update([
                    'check_out_time' => $currentTime,
                    'check_out_location' => $request->input('location', 'Unknown'),
                    'check_out_latitude' => $latitude,
                    'check_out_longitude' => $longitude
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

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}