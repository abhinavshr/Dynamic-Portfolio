<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AdminAuthController
 * @package App\Http\Controllers\api\admin
 *
 * Handles the authentication logic for the admin users.
 */
class AdminAuthController extends Controller
{
    /**
     * Register a new admin user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // Use a transaction to ensure data integrity
        return DB::transaction(function () use ($request) {
            try {
                // Check if singleton admin already exists (Guard clause)
                if (User::where('singleton', true)->exists()) {
                    return $this->errorResponse('Only one singleton admin user is allowed.', 403);
                }

                // Validate request
                $validated = $request->validate([
                    'name'          => 'required|string|max:255',
                    'email'         => 'required|email|unique:users,email',
                    'password'      => 'required|confirmed|min:6',
                    'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $profilePhotoUrl = null;

                // Handle image upload with robust logic
                if ($request->hasFile('profile_photo')) {
                    try {
                        $uploadResult = Cloudinary::upload(
                            $request->file('profile_photo')->getRealPath(),
                            [
                                'folder'    => 'admin_profile',
                                'public_id' => Str::slug($validated['name']) . '-' . time(),
                                'overwrite' => true,
                            ]
                        );
                        $profilePhotoUrl = $uploadResult->getSecurePath();
                    } catch (\Exception $e) {
                        Log::error('Cloudinary Upload Error: ' . $e->getMessage());
                        return $this->errorResponse('Failed to upload profile photo.', 500);
                    }
                }

                // Create admin user using Hash::make for standard hashing
                $admin = User::create([
                    'name'          => $validated['name'],
                    'email'         => $validated['email'],
                    'password'      => Hash::make($validated['password']),
                    'profile_photo' => $profilePhotoUrl,
                    'singleton'     => true,
                ]);

                return $this->successResponse('Admin registered successfully.', $admin, 201);
            } catch (\Exception $e) {
                Log::error('Admin Registration Error: ' . $e->getMessage());
                return $this->errorResponse('An error occurred during registration.', 500);
            }
        });
    }

    /**
     * Login an admin user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Get credentials and validate explicitly
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to login with admin guard
            if (!$token = Auth::guard('admin')->attempt($credentials)) {
                return $this->errorResponse('Invalid credentials.', 401);
            }

            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            Log::error('Admin Login Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred during login.', 500);
        }
    }

    /**
     * Return the token and other information.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'success'      => true,
            'message'      => 'Login successful.',
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    /**
     * Logout an admin user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Check if token exists before invalidating
            $token = JWTAuth::getToken();
            if (!$token) {
                return $this->errorResponse('No active session found.', 400);
            }

            JWTAuth::invalidate($token);

            return $this->successResponse('Successfully logged out.');
        } catch (JWTException $e) {
            Log::error('Admin Logout Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to logout. Please try again.', 500);
        }
    }

    /**
     * Standard success response structure.
     *
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(string $message, mixed $data = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Standard error response structure.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}

