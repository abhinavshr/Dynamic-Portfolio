<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;
use App\Models\User;

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
}
