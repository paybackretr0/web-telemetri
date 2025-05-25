<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use App\Models\User;
use App\Models\DutySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DelegationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $delegations = Delegation::with(['requester', 'delegate', 'dutySchedule', 'approver'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('requester', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('delegate', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('reason', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(6);

        // Get users and duty schedules for create modal
        $users = User::orderBy('name')->get();
        $dutySchedules = DutySchedule::orderBy('day_of_week')->get();

        return view('admin.delegation.index', compact('delegations', 'search', 'users', 'dutySchedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requester_id' => 'required|exists:users,id',
            'delegate_id' => 'required|exists:users,id|different:requester_id',
            'duty_schedule_id' => 'required|exists:duty_schedules,id',
            'delegation_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:1000',
        ], [
            'requester_id.required' => 'Pemohon harus dipilih',
            'requester_id.exists' => 'Pemohon tidak valid',
            'delegate_id.required' => 'Pengganti harus dipilih',
            'delegate_id.exists' => 'Pengganti tidak valid',
            'delegate_id.different' => 'Pengganti harus berbeda dengan pemohon',
            'duty_schedule_id.required' => 'Jadwal piket harus dipilih',
            'duty_schedule_id.exists' => 'Jadwal piket tidak valid',
            'delegation_date.required' => 'Tanggal pergantian harus diisi',
            'delegation_date.date' => 'Format tanggal tidak valid',
            'delegation_date.after_or_equal' => 'Tanggal pergantian tidak boleh di masa lalu',
            'reason.required' => 'Alasan harus diisi',
            'reason.max' => 'Alasan maksimal 1000 karakter',
        ]);

        // Check if there's already a delegation for the same date and duty schedule
        $existingDelegation = Delegation::where('duty_schedule_id', $request->duty_schedule_id)
            ->where('delegation_date', $request->delegation_date)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingDelegation) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah ada pergantian piket untuk jadwal dan tanggal yang sama'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $delegation = Delegation::create([
                'requester_id' => $request->requester_id,
                'delegate_id' => $request->delegate_id,
                'duty_schedule_id' => $request->duty_schedule_id,
                'delegation_date' => $request->delegation_date,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pergantian piket berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data'
            ], 500);
        }
    }

    public function show($id)
    {
        $delegation = Delegation::with(['requester', 'delegate', 'dutySchedule', 'approver'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $delegation->id,
                'requester' => $delegation->requester->name,
                'delegate' => $delegation->delegate->name,
                'duty_schedule' => $delegation->dutySchedule->day_of_week . ' (' . 
                    $delegation->dutySchedule->start_time->format('H:i') . ' - ' . 
                    $delegation->dutySchedule->end_time->format('H:i') . ')',
                'delegation_date' => $delegation->delegation_date->format('d M Y'),
                'reason' => $delegation->reason,
                'status' => $delegation->status,
                'status_label' => $this->getStatusLabel($delegation->status),
                'approver' => $delegation->approver ? $delegation->approver->name : null,
                'approved_at' => $delegation->approved_at ? $delegation->approved_at->format('d M Y H:i') : null,
                'created_at' => $delegation->created_at->format('d M Y H:i'),
            ]
        ]);
    }

    public function edit($id)
    {
        $delegation = Delegation::with(['requester', 'delegate', 'dutySchedule'])->findOrFail($id);
        
        if ($delegation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pergantian dengan status pending yang dapat diedit'
            ], 400);
        }

        $users = User::orderBy('name')->get();
        $dutySchedules = DutySchedule::orderBy('day_of_week')->get();

        return response()->json([
            'success' => true,
            'data' => $delegation,
            'users' => $users,
            'duty_schedules' => $dutySchedules
        ]);
    }

    public function update(Request $request, $id)
    {
        $delegation = Delegation::findOrFail($id);
        
        if ($delegation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pergantian dengan status pending yang dapat diedit'
            ], 400);
        }

        $request->validate([
            'requester_id' => 'required|exists:users,id',
            'delegate_id' => 'required|exists:users,id|different:requester_id',
            'duty_schedule_id' => 'required|exists:duty_schedules,id',
            'delegation_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:1000',
        ], [
            'requester_id.required' => 'Pemohon harus dipilih',
            'requester_id.exists' => 'Pemohon tidak valid',
            'delegate_id.required' => 'Pengganti harus dipilih',
            'delegate_id.exists' => 'Pengganti tidak valid',
            'delegate_id.different' => 'Pengganti harus berbeda dengan pemohon',
            'duty_schedule_id.required' => 'Jadwal piket harus dipilih',
            'duty_schedule_id.exists' => 'Jadwal piket tidak valid',
            'delegation_date.required' => 'Tanggal pergantian harus diisi',
            'delegation_date.date' => 'Format tanggal tidak valid',
            'delegation_date.after_or_equal' => 'Tanggal pergantian tidak boleh di masa lalu',
            'reason.required' => 'Alasan harus diisi',
            'reason.max' => 'Alasan maksimal 1000 karakter',
        ]);

        // Check if there's already a delegation for the same date and duty schedule (excluding current)
        $existingDelegation = Delegation::where('duty_schedule_id', $request->duty_schedule_id)
            ->where('delegation_date', $request->delegation_date)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $id)
            ->first();

        if ($existingDelegation) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah ada pergantian piket untuk jadwal dan tanggal yang sama'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $delegation->update([
                'requester_id' => $request->requester_id,
                'delegate_id' => $request->delegate_id,
                'duty_schedule_id' => $request->duty_schedule_id,
                'delegation_date' => $request->delegation_date,
                'reason' => $request->reason,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pergantian piket berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data'
            ], 500);
        }
    }

    public function approve($id)
    {
        $delegation = Delegation::findOrFail($id);
        
        if ($delegation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pergantian dengan status pending yang dapat disetujui'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $delegation->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pergantian piket berhasil disetujui'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui pergantian'
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $delegation = Delegation::findOrFail($id);
        
        if ($delegation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pergantian dengan status pending yang dapat ditolak'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $delegation->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pergantian piket berhasil ditolak'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak pergantian'
            ], 500);
        }
    }

    public function cancel($id)
    {
        $delegation = Delegation::findOrFail($id);
        
        if (!in_array($delegation->status, ['pending', 'approved'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pergantian ini tidak dapat dibatalkan'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $delegation->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pergantian piket berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pergantian'
            ], 500);
        }
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$status] ?? $status;
    }
}