<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrDutyController extends Controller
{
    public function index()
    {
        $qrcodes = QrCode::with('creator')
            ->where('type', 'duty')
            ->latest()
            ->paginate(10);

        return view('admin.qrduty.index', compact('qrcodes'));
    }

    public function store(Request $request)
    {
        $qrcode = QrCode::create([
            'code' => Str::random(10),
            'type' => 'duty',
            'expiry_time' => now()->addHours($request->expiry_time),
            'created_by' => auth()->user->id()
        ]);

        return redirect()
            ->route('admin.qrduty.index')
            ->with('success', 'QR Code piket berhasil dibuat.');
    }

    public function destroy($id)
    {
        $qrcode = QrCode::findOrFail($id);
        $qrcode->delete();

        return redirect()
            ->route('admin.qrduty.index')
            ->with('success', 'QR Code piket berhasil dihapus.');
    }
}