<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Mendapatkan data profil pengguna yang sedang login
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        $user = Auth::user();
       
        // Muat relasi jadwal piket
        $userWithDutySchedules = User::with('dutySchedules')->find($user->id);
       
        return response()->json([
            'success' => true,
            'data' => $userWithDutySchedules
        ]);
    }
   
    /**
     * Memperbarui data profil pengguna
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {        
        $user = Auth::user();
       
        $userModel = User::find($user->id);
       
        if (!$userModel) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
       
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:20',
            'nim' => 'sometimes|nullable|string|max:20',
            'jurusan' => 'sometimes|nullable|string|max:100',
            'nomor_seri' => 'sometimes|nullable|string|max:20',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
       
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
       
        // Update data profil
        if ($request->has('name')) {
            $userModel->name = $request->name;
        }
       
        if ($request->has('phone_number')) {
            $userModel->phone_number = $request->phone_number;
        }
       
        if ($request->has('nim')) {
            $userModel->nim = $request->nim;
        }
       
        if ($request->has('jurusan')) {
            $userModel->jurusan = $request->jurusan;
        }

        if ($request->has('nomor_seri')) {
            $userModel->nomor_seri = $request->nomor_seri;
        }
       
        if ($request->hasFile('profile_picture')) {
            try {
                if ($userModel->profile_picture) {
                    Storage::disk('public')->delete($userModel->profile_picture);
                }
                
                $file = $request->file('profile_picture');
                $path = $file->store('profile-pictures', 'public');
                $userModel->profile_picture = $path;
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunggah foto profil: ' . $e->getMessage()
                ], 500);
            }
        }

        $userModel->save();
        $freshUser = User::find($userModel->id);
                        
        $data = $freshUser->toArray();
        $data['profile_picture_url'] = $freshUser->profile_picture 
            ? asset('storage/' . $freshUser->profile_picture) 
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $data,
        ]);
    }
}