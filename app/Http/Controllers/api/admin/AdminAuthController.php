<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Check if singleton admin already exists
        if (User::where('singleton', true)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Only one singleton admin user is allowed.'
            ], 403);
        }

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload image to Cloudinary
        $profilePhotoUrl = null;

        if ($request->hasFile('profile_photo')) {
            $uploadResult = Cloudinary::upload(
                $request->file('profile_photo')->getRealPath(),
                [
                    'folder' => 'admin_profile',
                    'public_id' => Str::slug($validated['name']) . '-' . time(),
                    'overwrite' => true,
                ]
            );

            $profilePhotoUrl = $uploadResult->getSecurePath();
        }

        // Create admin user
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'profile_photo' => $profilePhotoUrl,
            'singleton' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin registered successfully.',
            'data' => $admin
        ], 201);
    }


    /**
     * Login an admin user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Get the credentials from the request.
        $credentials = $request->only(['email', 'password']);

        // Attempt to login the admin user.
        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        // Return the token and other information.
        return $this->respondWithToken($token);
    }

    /**
     * Return the token and other information.
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    /**
     * Logout an admin user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            // Invalidate the token.
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout. Please try again.'
            ], 500);
        }
    }
}
