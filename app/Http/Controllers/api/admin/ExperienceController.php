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
        // Validate the incoming request data
        $validatedData = $this->validateExperience($request);

        // Create a new experience entry with validated data
        $experience = Experience::create($validatedData);

        // Return success response with the newly created experience data
        return response()->json(
            [
                'message' => 'Experience created successfully',
                'data' => $experience
            ],
            201
        );
    }

    /**
     * Display a listing of all experiences.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAllExperiences(Request $request)
    {
        // Retrieve all experience entries from the database
        $experiences = Experience::all();

        // Return success response with all experiences data
        return response()->json([
            'message' => 'All experiences retrieved successfully',
            'data' => $experiences
        ]);
    }

    /**
     * Display the specified experience by ID.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewOneByOneExperience(Request $request, $id)
    {
        // Find the experience entry by ID
        $experience = Experience::find($id);
        if (!$experience) {
            // Return error response if experience entry not found
            return response()->json(['error' => 'Experience not found'], 404);
        }

        // Return success response with the specific experience data
        return response()->json([
            'message' => 'Experience retrieved successfully',
            'data' => $experience
        ]);
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
        // Validate the incoming request data
        $validatedData = $this->validateExperience($request);

        // Find the experience entry by ID
        $experience = Experience::find($id);

        // Update the experience entry with validated data
        $experience->update($validatedData);

        // Return success response with updated experience data
        return response()->json(
            [
                'message' => 'Experience updated successfully',
                'data' => $experience
            ],
            200
        );
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
        // Define validation rules for experience data
        return $request->validate([
            'company_name' => 'required|string|max:255',
            'role'         => 'required|string|max:255',
            'start_date'   => 'required|date_format:Y-m-d',
            'end_date'     => 'nullable|date_format:Y-m-d',
            'description'  => 'nullable|string',
        ]);
    }
}

