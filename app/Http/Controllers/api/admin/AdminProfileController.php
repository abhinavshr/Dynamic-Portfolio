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
     * Update the basic information for the currently logged in admin user.
     *
     * Validates the request and updates the basic information in the database.
     * Returns a JSON response with the status, message and the updated profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBasicInfo(Request $request)
    {
        $request->validate([
            'phone_number' => 'nullable|string|max:20',
            'professional_title' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'about_me' => 'nullable|string',
        ]);

        $profile = AdminProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'phone_number',
                'professional_title',
                'tagline',
                'about_me'
            ])
        );

        return response()->json([
            'status' => true,
            'message' => 'Basic information updated successfully',
            'data' => $profile
        ]);
    }

    /**
     * Update the portfolio statistics for the currently logged in admin user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam int years_of_experience The number of years of experience the admin user has.
     * @bodyParam int projects_completed The number of projects the admin user has completed.
     * @bodyParam int happy_clients The number of happy clients the admin user has worked with.
     * @bodyParam int technologies_used The number of technologies the admin user has used.
     */
    public function updatePortfolioStats(Request $request)
    {
        $request->validate([
            'years_of_experience' => 'nullable|integer|min:0',
            'projects_completed' => 'nullable|integer|min:0',
            'happy_clients' => 'nullable|integer|min:0',
            'technologies_used' => 'nullable|integer|min:0',
        ]);

        $profile = AdminProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'years_of_experience',
                'projects_completed',
                'happy_clients',
                'technologies_used'
            ])
        );

        return response()->json([
            'status' => true,
            'message' => 'Portfolio statistics updated successfully',
            'data' => $profile
        ]);
    }

    /**
     * Update the social links for the admin's profile.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSocialLinks(Request $request)
    {
        $request->validate([
            'github_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'cv_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
        ]);

        $profile = AdminProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'github_url',
                'linkedin_url',
                'cv_url',
                'twitter_url'
            ])
        );

        return response()->json([
            'status' => true,
            'message' => 'Social links updated successfully',
            'data' => $profile
        ]);
    }
}
