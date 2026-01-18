<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Constructor to apply middleware for authentication
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a newly created experience in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeExperience(Request $request)
    {
        $validatedData = $this->validateExperience($request);

        // If currently working, end_date must be null
        if ($validatedData['is_current']) {
            $validatedData['end_date'] = null;
        }

        $experience = Experience::create($validatedData);

        return response()->json([
            'message' => 'Experience created successfully',
            'data' => $experience
        ], 201);
    }

    /**
     * Display a listing of all experiences.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAllExperiences()
    {
        $experiences = Experience::orderBy('start_date', 'desc')->paginate(6);

        return response()->json([
            'message' => 'All experiences retrieved successfully',
            'data' => $experiences
        ], 200);
    }

    /**
     * Display the specified experience by ID.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewOneByOneExperience($id)
    {
        $experience = Experience::find($id);

        if (!$experience) {
            return response()->json([
                'error' => 'Experience not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Experience retrieved successfully',
            'data' => $experience
        ], 200);
    }

    /**
     * Update the specified experience in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateExperience(Request $request, $id)
    {
        $experience = Experience::find($id);

        if (!$experience) {
            return response()->json([
                'error' => 'Experience not found'
            ], 404);
        }

        $validatedData = $this->validateExperience($request);

        // If currently working, end_date must be null
        if ($validatedData['is_current']) {
            $validatedData['end_date'] = null;
        }

        $experience->update($validatedData);

        return response()->json([
            'message' => 'Experience updated successfully',
            'data' => $experience
        ], 200);
    }

    /**
     * Remove the specified experience from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteExperience(Request $request, $id)
    {
        // Find the experience entry by ID
        $experience = Experience::find($id);

        // Delete the experience entry
        $experience->delete();

        // Return success response indicating deletion
        return response()->json(
            [
                'message' => 'Experience deleted successfully'
            ],
            200
        );
    }

    /**
     * Validate the request data for experience.
     *
     * @param Request $request
     * @return array
     */
    private function validateExperience(Request $request)
    {
        return $request->validate([
            'company_name'     => 'required|string|max:255',
            'company_location' => 'required|string|max:255',
            'role'             => 'required|string|max:255',
            'start_date'       => 'required|date_format:Y-m-d',
            'end_date'         => 'nullable|date_format:Y-m-d|required_if:is_current,false',
            'is_current'       => 'required|boolean',
            'description'      => 'nullable|string',
        ]);
    }
}
