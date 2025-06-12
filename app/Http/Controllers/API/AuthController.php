<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;
use Google_Client;

class AuthController extends Controller
{
    /**
     * Authenticate user using Google token from mobile app
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
            'access_token' => 'nullable|string',
            'refresh_token' => 'nullable|string', 
            'device_token' => 'nullable|string', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $idToken = $request->id_token;
            $accessToken = $request->access_token ?? null;
        
            $tokenParts = explode(".", $idToken);
            if (count($tokenParts) >= 2) {
                $payload = base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1])));
                $userData = json_decode($payload, true);
            
                if (!isset($userData['email']) || !isset($userData['sub'])) {
                    throw new Exception('Invalid token data');
                }
                
                $existingUser = User::where('email', $userData['email'])->first();
                $isAdmin = User::where('email', $userData['email'])->where('role', 'admin')->exists();
                
                if (!$existingUser) {
                    return response()->json([
                        'message' => 'Email Anda tidak terdaftar di sistem',
                        'error' => 'Email tidak terdaftar di sistem'
                    ], 403);
                }

                if ($existingUser->role !== 'pengurus') {
                    return response()->json([
                        'message' => 'Akses ditolak. Hanya pengurus yang dapat menggunakan aplikasi mobile',
                        'error' => 'Role tidak memiliki akses'
                    ], 403);
                }
                
                $tokenData = [
                    'access_token' => $accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'created' => time()
                ];
                
                $existingUser->update([
                    'google_id' => $userData['sub'],
                    'google_token' => json_encode($tokenData),
                    'google_refresh_token' => $request->refresh_token ?? $existingUser->google_refresh_token,
                    'device_token' => $request->device_token ?? $existingUser->device_token, // Save device_token
                ]);
                
                $token = $existingUser->createToken('auth_token')->plainTextToken;
            
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $existingUser,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'has_calendar_access' => !empty($accessToken)
                ]);
            } else {
                throw new Exception('Invalid token format');
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login Gagal',
                'error' => $e->getMessage()
            ], 401);
        }
    }
    /**
     * Logout user (Revoke the token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Berhasil Keluar'
        ]);
    }
    /**
     * Refresh a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Refresh Google token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshGoogleToken(Request $request)
    {
        try {
            $user = $request->user();
            
            if (empty($user->google_refresh_token)) {
                return response()->json([
                    'message' => 'Tidak ada refresh token Google',
                    'error' => 'Refresh token tidak tersedia'
                ], 400);
            }
            
            $client = new Google_Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            
            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $tokens = $client->getAccessToken();
            
            $user->update([
                'google_token' => json_encode($tokens)
            ]);
            
            return response()->json([
                'message' => 'Token Google berhasil diperbarui',
                'has_calendar_access' => true
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui token Google',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->device_token = $request->device_token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Device token updated successfully',
        ]);
    }
}