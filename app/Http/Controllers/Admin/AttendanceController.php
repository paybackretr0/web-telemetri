<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\QrCode;
use App\Models\AttendanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of activities and meetings.
     */
    public function index(Request $request)
    {
        $activities = Activity::with(['qrCode', 'creator', 'attendanceType'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->latest('start_time')
            ->paginate(10)
            ->withQueryString();

        $meetings = Meeting::with(['creator'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->latest('meeting_date')
            ->paginate(10)
            ->withQueryString();

        $attendanceTypes = AttendanceType::all();

        return view('admin.attendance.index', compact('activities', 'meetings', 'attendanceTypes'));
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
            'type' => 'required|in:activity,meeting',
            'attendance_type_id' => 'required_if:type,activity|exists:attendance_types,id',
            'generate_qr' => 'nullable|boolean',
        ]);

        try {
            // Combine date and time
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['event_date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['event_date'] . ' ' . $validated['end_time']);

            // Validate end_time is after start_time
            if ($endDateTime <= $startDateTime) {
                return response()->json([
                    'success' => false,
                    'errors' => ['end_time' => ['Jam selesai harus setelah jam mulai.']]
                ], 422);
            }

            if ($validated['type'] === 'activity') {
                $activity = Activity::create([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'location' => $validated['location'],
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'attendance_type_id' => $validated['attendance_type_id'],
                    'created_by' => auth()->id(),
                ]);

                if ($request->input('generate_qr', false)) {
                    $this->generateQrCode($activity);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Kegiatan berhasil ditambahkan.'
                ]);
            } else {
                Meeting::create([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'location' => $validated['location'],
                    'meeting_date' => $startDateTime,
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'created_by' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Rapat berhasil ditambahkan.'
                ]);
            }
        } catch (\Exception $e) {
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
            $model = $type === 'activity' ? Activity::with('qrCode')->findOrFail($id) : Meeting::findOrFail($id);
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'attendance_type_id' => 'required_if:type,activity|exists:attendance_types,id',
            'regenerate_qr' => 'nullable|boolean',
        ]);

        try {
            // Combine date and time
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['event_date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['event_date'] . ' ' . $validated['end_time']);

            // Validate end_time is after start_time
            if ($endDateTime <= $startDateTime) {
                return response()->json([
                    'success' => false,
                    'errors' => ['end_time' => ['Jam selesai harus setelah jam mulai.']]
                ], 422);
            }

            if ($type === 'activity') {
                $model = Activity::findOrFail($id);
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
                $model = Meeting::findOrFail($id);
                $model->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'location' => $validated['location'],
                    'meeting_date' => $startDateTime,
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Rapat berhasil diperbarui.'
                ]);
            }
        } catch (\Exception $e) {
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
            $model = $type === 'activity' ? Activity::findOrFail($id) : Meeting::findOrFail($id);
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
    public function showQrCode($id)
    {
        try {
            $activity = Activity::with('qrCode')->findOrFail($id);
            
            if (!$activity->qrCode) {
                $qrCode = $this->generateQrCode($activity);
            } else {
                $qrCode = $activity->qrCode;
            }

            $qrCodeImage = \QrCode::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrCode->code);

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $activity->title,
                    'qr_image_url' => 'data:image/png;base64,' . base64_encode($qrCodeImage),
                    'expiry_time' => $qrCode->expiry_time->format('d M Y H:i'),
                ]
            ]);
        } catch (\Exception $e) {
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
            $activity = Activity::findOrFail($id);
            if ($activity->qrCode) {
                $activity->qrCode->delete();
            }
            $qrCode = $this->generateQrCode($activity);

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
     * Download QR code for an activity.
     */
    public function downloadQrCode($id)
    {
        try {
            $activity = Activity::with('qrCode')->findOrFail($id);
            
            if (!$activity->qrCode) {
                $qrCode = $this->generateQrCode($activity);
            } else {
                $qrCode = $activity->qrCode;
            }

            $qrCodeImage = \QrCode::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrCode->code);

            return response($qrCodeImage)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="qrcode-activity-' . $id . '.png"');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunduh QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a new QR code for an activity.
     */
    private function generateQrCode($activity)
    {
        $code = Str::random(10) . '-' . $activity->id . '-' . time();
        return QrCode::create([
            'activity_id' => $activity->id,
            'code' => $code,
            'type' => 'attendance',
            'expiry_time' => $activity->end_time,
            'created_by' => auth()->id(),
        ]);
    }
}