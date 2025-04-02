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
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            // Get the Google user directly from token without stateless
            $idToken = $request->id_token;
            
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
                
                // Find or create user
                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'] ?? $userData['email'],
                        'google_id' => $userData['sub'],
                        'password' => bcrypt(Str::random(16)),
                        'role' => 'pengurus'
                    ]
                );
                
                // Create token
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                throw new Exception('Invalid token format');
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Authentication failed',
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
            'message' => 'Successfully logged out'
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