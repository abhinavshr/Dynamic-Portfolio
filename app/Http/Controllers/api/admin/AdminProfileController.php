<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class AdminProfileController
 * @package App\Http\Controllers\api\admin
 *
 * Handles the profile management for admin users.
 */
class AdminProfileController extends Controller
{
    /**
     * Return the authenticated user and its profile.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $profile = AdminProfile::firstOrCreate(
                ['user_id' => $user->id]
            );

            return response()->json([
                'success' => true,
                'user'    => [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'profile_photo' => $user->profile_photo,
                ],
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            Log::error('Admin Profile Show Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch profile info.', 500);
        }
    }

    /**
     * Store or update the complete admin profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'phone_number'        => 'nullable|string|max:20',
                'professional_title'  => 'nullable|string|max:255',
                'tagline'             => 'nullable|string|max:255',
                'about_me'            => 'nullable|string',
                'years_of_experience' => 'nullable|integer|min:0',
                'projects_completed'  => 'nullable|integer|min:0',
                'happy_clients'       => 'nullable|integer|min:0',
                'technologies_used'   => 'nullable|integer|min:0',
                'github_url'          => 'nullable|url',
                'linkedin_url'        => 'nullable|url',
                'cv_url'              => 'nullable|url',
                'twitter_url'         => 'nullable|url',
            ]);

            $profile = AdminProfile::updateOrCreate(
                ['user_id' => Auth::id()],
                $validated
            );

            return $this->successResponse('Profile saved successfully', $profile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Admin Profile Store Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while saving the profile.', 500);
        }
    }

    /**
     * Update the basic information for the currently logged in admin user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBasicInfo(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'phone_number'       => 'nullable|string|max:20',
                'professional_title' => 'nullable|string|max:255',
                'tagline'            => 'nullable|string|max:255',
                'about_me'           => 'nullable|string',
            ]);

            $profile = AdminProfile::updateOrCreate(
                ['user_id' => Auth::id()],
                $validated
            );

            return $this->successResponse('Basic information updated successfully', $profile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Admin Basic Info Update Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to update basic information.', 500);
        }
    }

    /**
     * Update the portfolio statistics for the currently logged in admin user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePortfolioStats(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'years_of_experience' => 'nullable|integer|min:0',
                'projects_completed'  => 'nullable|integer|min:0',
                'happy_clients'       => 'nullable|integer|min:0',
                'technologies_used'   => 'nullable|integer|min:0',
            ]);

            $profile = AdminProfile::updateOrCreate(
                ['user_id' => Auth::id()],
                $validated
            );

            return $this->successResponse('Portfolio statistics updated successfully', $profile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Admin Portfolio Stats Update Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to update portfolio statistics.', 500);
        }
    }

    /**
     * Update the social links for the admin's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSocialLinks(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'github_url'   => 'nullable|url',
                'linkedin_url' => 'nullable|url',
                'cv_url'       => 'nullable|url',
                'twitter_url'  => 'nullable|url',
            ]);

            $profile = AdminProfile::updateOrCreate(
                ['user_id' => Auth::id()],
                $validated
            );

            return $this->successResponse('Social links updated successfully', $profile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Admin Social Links Update Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to update social links.', 500);
        }
    }

    /**
     * Get the number of years of experience for the currently logged in admin user.
     *
     * @return JsonResponse
     */
    public function getExperience(): JsonResponse
    {
        try {
            $profile = AdminProfile::where('user_id', Auth::id())->first();

            return response()->json([
                'success'             => true,
                'message'             => 'Years of experience fetched successfully',
                'years_of_experience' => $profile->years_of_experience ?? 0
            ]);
        } catch (\Exception $e) {
            Log::error('Admin Get Experience Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch experience data.', 500);
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
            $response['profile'] = $data;
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

