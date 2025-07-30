<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\QrCode as qr;
use App\Models\AttendanceType;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator; 
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of activities and meetings.
     */
    public function index(Request $request)
    {
        $meetingTypeId = AttendanceType::where('name', 'Rapat')->value('id');

        $activities = Activity::with(['qrCode', 'creator', 'attendanceType'])
            ->where(function ($query) use ($meetingTypeId) {
                if ($meetingTypeId) {
                    $query->where('attendance_type_id', '!=', $meetingTypeId);
                }
            })
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'activities_page') 
            ->withQueryString();

        $meetings = Activity::with(['qrCode', 'creator', 'attendanceType'])
            ->where('attendance_type_id', $meetingTypeId)
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10, ['*'], 'meetings_page') 
            ->withQueryString();

        $attendanceTypes = AttendanceType::where('name', '!=', 'Piket')->get();

        return view('admin.attendance.index', compact('activities', 'meetings', 'attendanceTypes'));
    }

    /**
     * Create initial attendance records for all users for a given event.
     */
    private function createInitialAttendanceRecords($event)
    {
        $isActivity = $event instanceof Activity;

        $users = User::where('role', '!=', 'admin')->get();

        $attendanceData = [];
        $now = now();

        foreach ($users as $user) {
            $attendanceData[] = [
                'user_id'       => $user->id,
                'activity_id'   => $isActivity ? $event->id : null,
                'meeting_id'    => !$isActivity ? $event->id : null,
                'status'        => 'alfa', 
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        if (!empty($attendanceData)) {
            Attendance::insert($attendanceData);
        }
    }

    /**
     * Store a new activity or meeting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'attendance_type_id' => 'required_if:type,activity|exists:attendance_types,id',
            'generate_qr' => 'nullable|boolean',
        ]);

        try {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['event_date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['event_date'] . ' ' . $validated['end_time']);

            if ($endDateTime <= $startDateTime) {
                return response()->json([
                    'success' => false,
                    'errors' => ['end_time' => ['Jam selesai harus setelah jam mulai.']]
                ], 422);
            }

            $activity = Activity::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'attendance_type_id' => $validated['attendance_type_id'],
                'created_by' => auth()->id(),
            ]);

            $activity->refresh();

            $this->createInitialAttendanceRecords($activity);

            if ($request->input('generate_qr', false)) {
                $this->generateQrCode($activity);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kegiatan/Rapat berhasil ditambahkan.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error store attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing an activity or meeting.
     */
    public function edit($id, $type)
    {
        try {
            $model = Activity::with('qrCode')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $model
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Update an existing activity or meeting.
     */
    public function update(Request $request, $id, $type)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'sometimes|required|string|max:255',
            'event_date' => 'sometimes|required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'attendance_type_id' => 'sometimes|required_if:type,activity|exists:attendance_types,id',
            'regenerate_qr' => 'nullable|boolean',
        ]);
        

        try {
            $model = Activity::with('qrCode')->findOrFail($id);
            
            $eventDate = $validated['event_date'] ?? ($type === 'activity' ? $model->start_time->format('Y-m-d') : $model->meeting_date->format('Y-m-d'));
            $startTime = $validated['start_time'] ?? ($type === 'activity' ? $model->start_time->format('H:i') : $model->start_time->format('H:i'));
            $endTime = $validated['end_time'] ?? ($type === 'activity' ? $model->end_time->format('H:i') : $model->end_time->format('H:i'));

            $startDateTime = Carbon::parse($validated['start_time']);
            $endDateTime = Carbon::parse($validated['end_time']);

            if ($endDateTime <= $startDateTime) {
                return response()->json([
                    'success' => false,
                    'errors' => ['end_time' => ['Jam selesai harus setelah jam mulai.']]
                ], 422);
            }

            if ($type === 'activity') {
                $model->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'location' => $validated['location'],
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'attendance_type_id' => $validated['attendance_type_id'],
                ]);

                if ($request->input('regenerate_qr', false)) {
                    if ($model->qrCode) {
                        $model->qrCode->delete();
                    }
                    $this->generateQrCode($model);
                } elseif ($model->qrCode) {
                    $model->qrCode->update([
                        'expiry_time' => $endDateTime,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Kegiatan berhasil diperbarui.'
                ]);
            } else {
                $meetingDate = Carbon::createFromFormat('Y-m-d', $eventDate)->startOfDay();
                $model->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'location' => $validated['location'],
                    'meeting_date' => $meetingDate,
                    'start_time' => $startDateTime,  
                    'end_time' => $endDateTime,      
                ]);

                if ($request->input('regenerate_qr', false)) {
                    if ($model->qrCode) {
                        $model->qrCode->delete();
                    }
                    $this->generateQrCode($model);
                } elseif ($model->qrCode) {
                    $model->qrCode->update([
                        'expiry_time' => $endDateTime,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Rapat berhasil diperbarui.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error update attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove an activity or meeting.
     */
    public function destroy($id, $type)
    {
        try {
            $model = Activity::with('qrCode')->findOrFail($id);
            $model->delete();

            return response()->json([
                'success' => true,
                'message' => ($type === 'activity' ? 'Kegiatan' : 'Rapat') . ' berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
    * Show QR code for an activity.
    */
    public function showQrCode(Request $request, $id)
    {
        try {
            $model = Activity::with('qrCode')->findOrFail($id);

            if (!$model->qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code belum dibuat. Silakan edit kegiatan dan centang opsi "Generate QR Code".',
                ], 404);
            }

            $qrCodeImageUrl = Storage::url($model->qrCode->file_path);

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $model->title,
                    'qr_image_url' => $qrCodeImageUrl,
                    'expiry_time' => $model->qrCode->expiry_time->format('d M Y H:i'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error showQrCode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Regenerate QR code for an activity.
     */
    public function regenerateQrCode($id)
    {
        try {
            $model = Activity::find($id);
            
            if ($model->qrCode) {
                if ($model->qrCode->file_path && Storage::exists('public/' . $model->qrCode->file_path)) {
                    Storage::delete('public/' . $model->qrCode->file_path);
                }
                $model->qrCode->delete();
            }
            
            $qrCode = $this->generateQrCode($model);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil dibuat ulang.',
                'qr_code' => [
                    'expiry_time' => $qrCode->expiry_time->format('d M Y H:i')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat ulang QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a new QR code for an activity or meeting.
     */
    private function generateQrCode($model)
    {
        try {
            $code = Str::random(10) . '-' . $model->id . '-' . time();

            if (!$model->end_time instanceof \Carbon\Carbon) {
                Log::warning('Invalid end_time for model: ' . get_class($model) . ' ID: ' . $model->id . '. Using default expiry.');
                $expiryTime = Carbon::now()->addHour();
            } else {
                $expiryTime = $model->end_time;
            }

            if ($expiryTime->isPast()) {
                Log::warning('Expiry time is in the past for model: ' . get_class($model) . ' ID: ' . $model->id . '. Current: ' . Carbon::now()->toDateTimeString() . ', Expiry: ' . $expiryTime->toDateTimeString() . '. Setting to 1 hour from now.');
                $expiryTime = Carbon::now()->addHour();
            }

            $qrCodeData = [
                'code' => $code,
                'type' => $model instanceof Activity ? 'activity' : 'meeting',
                'expiry_time' => $expiryTime,
                'created_by' => auth()->id(),
            ];

            $qrCodeData['activity_id'] = $model->id;
            

            $qrCode = QrCode::create($code)
                ->setSize(300)
                ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $fileName = 'qrcodes/qrcode-' . ($model instanceof Activity ? 'activity' : 'meeting') . '-' . $model->id . '-' . time() . '.png';

            Storage::disk('public')->put($fileName, $result->getString());
            $qrCodeData['file_path'] = $fileName;

            return qr::create($qrCodeData);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}