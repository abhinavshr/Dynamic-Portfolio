<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // JWT returns GenericUser → get ID
        $adminId = Auth::guard('admin')->id();

        $user = User::findOrFail($adminId);

        // Delete old photo
        if ($user->profile_photo) {
            $this->deleteFromCloudinary($user->profile_photo);
        }

        // Upload new photo
        $uploadResult = Cloudinary::upload(
            $request->file('profile_photo')->getRealPath(),
            [
                'folder' => 'admin_profile',
                'public_id' => Str::slug($user->name) . '-' . time(),
                'overwrite' => true,
            ]
        );

        // ✅ Eloquent update works now
        $user->profile_photo = $uploadResult->getSecurePath();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully.',
            'profile_photo' => $user->profile_photo,
        ]);
    }

    /**
     * Deletes a file from Cloudinary using a secure URL.
     *
     * @param string $url The secure URL of the file to delete.
     *
     * @return void
     *
     * @throws \Exception If the deletion fails.
     */

    private function deleteFromCloudinary(string $url): void
    {
        try {
            $path = parse_url($url, PHP_URL_PATH);
            $publicId = pathinfo($path, PATHINFO_FILENAME);
            Cloudinary::destroy('admin_profile/' . $publicId);
        } catch (\Exception $e) {
            // silent fail
        }
    }

    /**
     * Update the admin profile.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function updateProfile(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $user = User::findOrFail($adminId);

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Changes the password of the currently logged in admin user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePassword(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $user = User::findOrFail($adminId);

        // Validate input
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // Check if new password is the same as current password
        if (Hash::check($validated['new_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password cannot be the same as the current password.',
            ], 400);
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}
