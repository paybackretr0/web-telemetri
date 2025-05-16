<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activities = Activity::with(['qrCode', 'creator'])
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

        return view('admin.attendance.index', [
            'activities' => $activities,
            'meetings' => $meetings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('admin.attendance.create');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'location' => 'required|string|max:255',
    //         'latitude' => 'nullable|numeric',
    //         'longitude' => 'nullable|numeric',
    //         'start_time' => 'required|date',
    //         'end_time' => 'required|date|after:start_time',
    //         'type' => 'required|in:activity,meeting',
    //     ]);

    //     if ($validated['type'] === 'activity') {
    //         $activity = Activity::create($validated + [
    //             'created_by' => auth()->user->id(),
    //         ]);

    //         // Generate QR Code for activity
    //         QrCode::create([
    //             'activity_id' => $activity->id,
    //             'code' => Str::random(10),
    //             'expiry_time' => $validated['end_time'],
    //             'created_by' => auth()->user->id(),
    //         ]);
    //     } else {
    //         Meeting::create($validated + [
    //             'meeting_date' => $validated['start_time'],
    //             'created_by' => auth()->user->id(),
    //         ]);
    //     }

    //     return redirect()
    //         ->route('admin.attendance.index')
    //         ->with('success', 'Kegiatan berhasil ditambahkan.');
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit($id, $type)
    // {
    //     $model = $type === 'activity' ? Activity::findOrFail($id) : Meeting::findOrFail($id);
    //     return view('admin.attendance.edit', [
    //         'model' => $model,
    //         'type' => $type,
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, $id, $type)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'location' => 'required|string|max:255',
    //         'latitude' => 'nullable|numeric',
    //         'longitude' => 'nullable|numeric',
    //         'start_time' => 'required|date',
    //         'end_time' => 'required|date|after:start_time',
    //     ]);

    //     if ($type === 'activity') {
    //         $model = Activity::findOrFail($id);
    //         $model->update($validated);

    //         // Update QR Code expiry time
    //         if ($model->qrCode) {
    //             $model->qrCode->update([
    //                 'expiry_time' => $validated['end_time'],
    //             ]);
    //         }
    //     } else {
    //         $model = Meeting::findOrFail($id);
    //         $model->update($validated + [
    //             'meeting_date' => $validated['start_time'],
    //         ]);
    //     }

    //     return redirect()
    //         ->route('admin.attendance.index')
    //         ->with('success', 'Kegiatan berhasil diperbarui.');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy($id, $type)
    // {
    //     $model = $type === 'activity' ? Activity::findOrFail($id) : Meeting::findOrFail($id);
    //     $model->delete();

    //     return redirect()
    //         ->route('admin.attendance.index')
    //         ->with('success', 'Kegiatan berhasil dihapus.');
    // }
}