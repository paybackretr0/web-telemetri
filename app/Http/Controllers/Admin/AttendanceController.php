<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\QrCode;
use App\Models\AttendanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
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

        return view('admin.attendance.index', [
            'activities' => $activities,
            'meetings' => $meetings,
            'attendanceTypes' => $attendanceTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => 'required|in:activity,meeting',
            'attendance_type_id' => 'required_if:type,activity|exists:attendance_types,id',
            'generate_qr' => 'nullable',
        ]);

        if ($validated['type'] === 'activity') {
            $activity = Activity::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'attendance_type_id' => $validated['attendance_type_id'],
                'created_by' => auth()->user->id(),
            ]);

            // Generate QR Code for activity if requested
            if ($request->has('generate_qr')) {
                $this->generateQrCode($activity);
            }

            return redirect()
                ->route('admin.attendance')
                ->with('success', 'Kegiatan berhasil ditambahkan.');
        } else {
            Meeting::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'meeting_date' => $validated['start_time'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'created_by' => auth()->user->id(),
            ]);

            return redirect()
                ->route('admin.attendance')
                ->with('success', 'Rapat berhasil ditambahkan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $type)
    {
        $model = $type === 'activity' ? Activity::findOrFail($id) : Meeting::findOrFail($id);
        return response()->json($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, $type)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'attendance_type_id' => 'required_if:type,activity|exists:attendance_types,id',
            'regenerate_qr' => 'nullable',
        ]);

        if ($type === 'activity') {
            $model = Activity::findOrFail($id);
            $model->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'attendance_type_id' => $validated['attendance_type_id'],
            ]);

            // Regenerate QR Code if requested
            if ($request->has('regenerate_qr')) {
                // Delete existing QR code if any
                if ($model->qrCode) {
                    $model->qrCode->delete();
                }
                
                // Generate new QR code
                $this->generateQrCode($model);
            } else if ($model->qrCode) {
                // Update QR Code expiry time
                $model->qrCode->update([
                    'expiry_time' => $validated['end_time'],
                ]);
            }
        } else {
            $model = Meeting::findOrFail($id);
            $model->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'meeting_date' => $validated['start_time'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
            ]);
        }

        return redirect()
            ->route('admin.attendance')
            ->with('success', ($type === 'activity' ? 'Kegiatan' : 'Rapat') . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $type)
    {
        $model = $type === 'activity' ? Activity::findOrFail($id) : Meeting::findOrFail($id);
        $model->delete();

        return redirect()
            ->route('admin.attendance')
            ->with('success', ($type === 'activity' ? 'Kegiatan' : 'Rapat') . ' berhasil dihapus.');
    }

    /**
     * Display QR code for the specified activity.
     */
    public function showQrCode($id)
    {
        $activity = Activity::with('qrCode')->findOrFail($id);
        
        if (!$activity->qrCode) {
            // Generate QR code if not exists
            $qrCode = $this->generateQrCode($activity);
        } else {
            $qrCode = $activity->qrCode;
        }

        // Generate QR code image
        $qrCodeImage = QrCodeGenerator::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrCode->code);

        return view('admin.attendance.qrcode', [
            'activity' => $activity,
            'qrCode' => $qrCode,
            'qrCodeImage' => base64_encode($qrCodeImage),
        ]);
    }

    /**
     * Generate QR code for an activity.
     */
    private function generateQrCode($activity)
    {
        // Generate a unique code
        $code = Str::random(10) . '-' . $activity->id . '-' . time();
        
        // Create QR code record
        return QrCode::create([
            'activity_id' => $activity->id,
            'code' => $code,
            'type' => 'attendance',
            'expiry_time' => $activity->end_time,
            'created_by' => auth()->user->id(),
        ]);
    }
}