<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{

    /**
     * Return the authenticated user and its profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user = Auth::user();

        $profile = AdminProfile::firstOrCreate(
            ['user_id' => $user->id]
        );

        return response()->json([
            'status' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo,
            ],
            'profile' => $profile
        ]);
    }

    /**
     * Store a new admin profile in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'nullable|string|max:20',
            'professional_title' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'about_me' => 'nullable|string',
            'years_of_experience' => 'nullable|integer|min:0',
            'projects_completed' => 'nullable|integer|min:0',
            'happy_clients' => 'nullable|integer|min:0',
            'technologies_used' => 'nullable|integer|min:0',
            'github_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'cv_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
        ]);

        $profile = AdminProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return response()->json([
            'status' => true,
            'message' => 'Profile saved successfully',
            'profile' => $profile
        ]);
    }
}
