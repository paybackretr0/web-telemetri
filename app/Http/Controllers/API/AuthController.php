<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;
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
            'access_token' => 'nullable|string', // Tambahkan validasi untuk access_token
        ]);
       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
       
        try {
            // Get the Google user directly from token without stateless
            $idToken = $request->id_token;
            $accessToken = $request->access_token ?? null; // Access token untuk Google Calendar
           
            // For testing, you can decode the token to get user info directly
            // This is a simplified approach - in production, you should verify with Google
            $tokenParts = explode(".", $idToken);
            if(count($tokenParts) >= 2) {
                $payload = base64_decode(str_replace('_', '/', str_replace('-','+', $tokenParts[1])));
                $userData = json_decode($payload, true);
               
                // Check if we have required information
                if(!isset($userData['email']) || !isset($userData['sub'])) {
                    throw new Exception('Invalid token data');
                }
                
                // Check if the email exists in the database
                $existingUser = User::where('email', $userData['email'])->first();
                
                if (!$existingUser) {
                    return response()->json([
                        'message' => 'Email Anda tidak terdaftar di sistem',
                        'error' => 'Email tidak terdaftar di sistem'
                    ], 403);
                }
                
                // Update user with Google credentials
                $existingUser->update([
                    'google_id' => $userData['sub'],
                    'google_token' => $accessToken
                ]);
                
                // Create token
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
}