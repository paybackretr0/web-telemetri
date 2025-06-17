<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DutySchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DutyController extends Controller
{
    public function index(Request $request)
    {
        $schedules = DutySchedule::with(['users' => function ($query) {
            $query->wherePivot('end_date', '>=', now())
                  ->orderBy('pivot_start_date', 'asc');
        }])
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->paginate(10);

        $users = User::where('role', 'pengurus')->get();

        return view('admin.duty.index', compact('schedules', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'users' => 'required|array|min:1',
            'users.*.id' => 'required|exists:users,id',
            'users.*.start_date' => 'required|date',
            'users.*.end_date' => 'required|date|after_or_equal:users.*.start_date',
        ]);

        try {
            $schedule = DutySchedule::create([
                'day_of_week' => $validated['day_of_week'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'location' => $validated['location'],
            ]);

            foreach ($validated['users'] as $user) {
                $schedule->users()->attach($user['id'], [
                    'start_date' => $user['start_date'],
                    'end_date' => $user['end_date'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Jadwal piket berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing duty schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan jadwal.',
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $schedule = DutySchedule::with(['users' => function ($query) {
                $query->withPivot('start_date', 'end_date');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $schedule->id,
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => \Carbon\Carbon::parse($schedule->start_time)->format('H:i'),
                    'end_time' => \Carbon\Carbon::parse($schedule->end_time)->format('H:i'),
                    'location' => $schedule->location,
                    'users' => $schedule->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'start_date' => \Carbon\Carbon::parse($user->pivot->start_date)->format('Y-m-d'),
                            'end_date' => \Carbon\Carbon::parse($user->pivot->end_date)->format('Y-m-d'),
                        ];
                    })->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching duty schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan.',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'day_of_week' => 'sometimes|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'location' => 'sometimes|string|max:255',
            'users' => 'sometimes|array|min:1',
            'users.*.id' => 'sometimes|exists:users,id',
            'users.*.start_date' => 'sometimes|date',
            'users.*.end_date' => 'sometimes|date|after_or_equal:users.*.start_date',
        ]);

        try {
            $schedule = DutySchedule::findOrFail($id);

            $schedule->update([
                'day_of_week' => $validated['day_of_week'] ?? $schedule->day_of_week,
                'start_time' => $validated['start_time'] ?? $schedule->start_time,
                'end_time' => $validated['end_time'] ?? $schedule->end_time,
                'location' => $validated['location'] ?? $schedule->location,
            ]);

            // Hanya update users jika dikirim
            if (isset($validated['users'])) {
                $users = collect($validated['users'])->mapWithKeys(function ($user) {
                    return [$user['id'] => [
                        'start_date' => $user['start_date'],
                        'end_date' => $user['end_date'],
                    ]];
                })->toArray();

                $schedule->users()->sync($users);
            }

            return response()->json([
                'success' => true,
                'message' => 'Jadwal piket berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating duty schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui jadwal.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = DutySchedule::findOrFail($id);
            $schedule->users()->detach(); // Remove all associated users
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal piket berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting duty schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jadwal.'
            ], 500);
        }
    }
}