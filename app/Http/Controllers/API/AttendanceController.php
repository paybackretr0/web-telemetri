<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QrCode;
use App\Models\Activity;
use App\Models\UserDutySchedule;
use App\Models\Delegation;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttendanceController extends Controller
{
    public function scanQrCode(Request $request, $code)
    {
        try {
            $qrcode = QrCode::where('code', $code)->firstOrFail();
            $currentTime = Carbon::now('Asia/Jakarta');

            if ($qrcode->expiry_time < $currentTime) {
                return response()->json(['success' => false, 'message' => 'QR Code telah kadaluarsa.'], 400);
            }

            $userId = auth()->id();
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            switch ($qrcode->type) {
                case 'activity': 
                    $event = Activity::find($qrcode->activity_id);
                        
                    if (!$event) {
                        return response()->json(['success' => false, 'message' => ucfirst($qrcode->type) . ' tidak ditemukan.'], 404);
                    }

                    if ($currentTime->isBefore($event->start_time) || $currentTime->isAfter($event->end_time)) {
                        return response()->json(['success' => false, 'message' => 'Di luar waktu presensi yang ditentukan.'], 400);
                    }

                    $attendance = Attendance::where('user_id', $userId)
                        ->when($qrcode->type === 'activity', fn($q) => $q->where('activity_id', $qrcode->activity_id))
                        ->when($qrcode->type === 'meeting', fn($q) => $q->where('meeting_id', $qrcode->meeting_id))
                        ->first();

                    if (!$attendance) {
                        return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar dalam presensi ini.'], 404);
                    }

                    if ($attendance->status === 'hadir') {
                        return response()->json(['success' => false, 'message' => 'Anda sudah melakukan presensi.'], 400);
                    }

                    $attendance->update([
                        'status' => 'hadir',
                        'check_in_time' => $currentTime,
                        'check_in_location' => $request->input('location', 'Unknown'),
                        'check_in_latitude' => $latitude,
                        'check_in_longitude' => $longitude,
                        'verified_by' => $qrcode->created_by,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Presensi berhasil diambil.',
                        'data' => $attendance,
                    ]);

                    break; 

                case 'duty':
                    $currentDate = Carbon::today('Asia/Jakarta');
                    
                    $existingAttendance = Attendance::where('user_id', $userId)
                        ->whereNull('activity_id')->whereNull('meeting_id') 
                        ->whereDate('check_in_time', $currentDate)
                        ->first();
                    
                    if (!$latitude || !$longitude) {
                        return response()->json(['success' => false, 'message' => 'Koordinat lokasi diperlukan untuk absen piket.'], 400);
                    }
                    $allowedLocations = config('duty_locations');
                    $isValidLocation = false;
                    foreach ($allowedLocations as $location) {
                        if ($this->calculateDistance($latitude, $longitude, $location['latitude'], $location['longitude']) <= $location['radius']) {
                            $isValidLocation = true;
                            break;
                        }
                    }
                    if (!$isValidLocation) {
                        return response()->json(['success' => false, 'message' => 'Anda tidak berada di lokasi piket yang diizinkan.'], 400);
                    }

                    if (!$existingAttendance) {
                        $dayMapping = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
                        $currentDayOfWeek = $dayMapping[$currentDate->englishDayOfWeek] ?? $currentDate->englishDayOfWeek;
                        
                        $isScheduled = UserDutySchedule::where('user_id', $userId)
                            ->whereHas('dutySchedule', fn($q) => $q->where('day_of_week', $currentDayOfWeek))
                            ->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)
                            ->exists();

                        $isDelegated = Delegation::where('delegate_id', $userId)
                            ->where('delegation_date', $currentDate)->where('status', 'approved')
                            ->exists();

                        if (!$isScheduled && !$isDelegated) {
                            return response()->json(['success' => false, 'message' => 'Bukan jadwal piket Anda.'], 400);
                        }
                        
                        $newAttendance = Attendance::create([
                            'user_id' => $userId,
                            'status' => 'hadir',
                            'check_in_time' => $currentTime,
                            'check_in_location' => $request->input('location', 'Unknown'),
                            'check_in_latitude' => $latitude,
                            'check_in_longitude' => $longitude,
                            'verified_by' => $qrcode->created_by,
                        ]);

                        return response()->json(['success' => true, 'message' => 'Check-in piket berhasil.', 'data' => $newAttendance]);
                    } 
                    else {
                        if ($existingAttendance->check_out_time) {
                            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-out hari ini.'], 400);
                        }

                        $minutesDifference = $existingAttendance->check_in_time->diffInMinutes($currentTime);
                        if ($minutesDifference < 120) {
                            return response()->json(['success' => false, 'message' => 'Check-out belum bisa dilakukan. Minimal 2 jam setelah check-in.'], 400);
                        }

                        $existingAttendance->update([
                            'check_out_time' => $currentTime,
                            'check_out_location' => $request->input('location', 'Unknown'),
                            'check_out_latitude' => $latitude,
                            'check_out_longitude' => $longitude,
                        ]);
                        
                        return response()->json(['success' => true, 'message' => 'Check-out piket berhasil.', 'data' => $existingAttendance]);
                    }
                    
                    break; 

                default:
                    return response()->json(['success' => false, 'message' => 'Tipe QR Code tidak valid.'], 400);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak ditemukan atau Anda tidak terdaftar.'], 404);
        } catch (\Exception $e) {
            Log::error('Error scanning QR: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
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