<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;

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
        try {
            $request->validate([
                'expiry_time' => 'required|numeric',
            ]);

            $code = Str::random(10);
            $expiryTime = Carbon::now()->addHours($request->expiry_time);

            // Generate QR Code image
            $qrCode = new EndroidQrCode($code);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);
            
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $fileName = 'qrcodes/qrcode-duty-' . time() . '.png';
            Storage::disk('public')->put($fileName, $result->getString());

            // Create QR Code record
            $qrcode = QrCode::create([
                'code' => $code,
                'file_path' => $fileName,
                'type' => 'duty',
                'expiry_time' => $expiryTime,
                'created_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code piket berhasil dibuat.',
                'data' => $qrcode
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating QR Duty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'expiry_time' => 'required|numeric',
            ]);

            $qrcode = QrCode::findOrFail($id);
            $expiryTime = Carbon::now()->addHours($request->expiry_time);
            
            $qrcode->update([
                'expiry_time' => $expiryTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code piket berhasil diperbarui.',
                'data' => $qrcode
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating QR Duty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $qrcode = QrCode::findOrFail($id);
            
            // Delete QR code image if exists
            if ($qrcode->file_path && Storage::disk('public')->exists($qrcode->file_path)) {
                Storage::disk('public')->delete($qrcode->file_path);
            }
            
            $qrcode->delete();

            return response()->json([
                'success' => true,
                'message' => 'QR Code piket berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting QR Duty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showQrCode($code)
    {
        try {
            $qrcode = QrCode::where('code', $code)->where('type', 'duty')->firstOrFail();
            
            $qrCodeImageUrl = Storage::url($qrcode->file_path);

            return response()->json([
                'success' => true,
                'data' => [
                    'code' => $qrcode->code,
                    'qr_image_url' => $qrCodeImageUrl,
                    'expiry_time' => $qrcode->expiry_time->format('d M Y H:i'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing QR Duty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}