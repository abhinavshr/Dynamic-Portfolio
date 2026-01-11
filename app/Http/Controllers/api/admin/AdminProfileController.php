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

    /**
     * Update the admin profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam string phone_number The admin's phone number.
     * @bodyParam string professional_title The admin's professional title.
     * @bodyParam string tagline The admin's tagline.
     * @bodyParam string about_me The admin's about me information.
     * @bodyParam integer years_of_experience The admin's years of experience.
     * @bodyParam integer projects_completed The admin's projects completed.
     * @bodyParam integer happy_clients The admin's happy clients.
     * @bodyParam integer technologies_used The admin's technologies used.
     * @bodyParam string github_url The admin's GitHub URL.
     * @bodyParam string linkedin_url The admin's LinkedIn URL.
     * @bodyParam string cv_url The admin's CV URL.
     * @bodyParam string twitter_url The admin's Twitter URL.
     */
    public function update(Request $request)
    {
        $request->validate([
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
            [
                'phone_number' => $request->phone_number,
                'professional_title' => $request->professional_title,
                'tagline' => $request->tagline,
                'about_me' => $request->about_me,
                'years_of_experience' => $request->years_of_experience,
                'projects_completed' => $request->projects_completed,
                'happy_clients' => $request->happy_clients,
                'technologies_used' => $request->technologies_used,
                'github_url' => $request->github_url,
                'linkedin_url' => $request->linkedin_url,
                'cv_url' => $request->cv_url,
                'twitter_url' => $request->twitter_url,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Admin information updated successfully',
            'data' => $profile
        ], 200);
    }
}
