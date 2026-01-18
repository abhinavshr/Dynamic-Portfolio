<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Constructor to apply middleware for admin authentication.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a new education entry in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeEducation(Request $request)
    {
        // Validate the request data
        $validatedData = $this->validateEducation($request);

        // Create a new education entry with validated data
        $education = Education::create($validatedData);

        // Return success response with the newly created education data
        return response()->json([
            'message' => 'Education entry created successfully',
            'data' => $education
        ], 201);
    }

    /**
     * Retrieve all education entries from the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAllEducations(Request $request)
    {

        $educations = Education::paginate(6);

        // Return success response with all education data
        return response()->json([
            'message' => 'All education entries retrieved successfully',
            'data' => $educations
        ]);
    }

    /**
     * Update an existing education entry in the database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEducation(Request $request, $id)
    {
        // Find the education entry by ID
        $education = Education::find($id);
        if (!$education) {
            // Return error response if education entry not found
            return response()->json(['error' => 'Education not found'], 404);
        }

        // Validate the request data
        $validatedData = $this->validateEducation($request);

        // Update the education entry with validated data
        $education->update($validatedData);

        // Return success response with updated education data
        return response()->json([
            'message' => 'Education updated successfully',
            'data' => $education
        ], 200);
    }

    /**
     * Delete an existing education entry from the database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEducation(Request $request, $id)
    {
        // Find the education entry by ID
        $education = Education::find($id);
        if (!$education) {
            // Return error response if education entry not found
            return response()->json(['error' => 'Education not found'], 404);
        }

        // Delete the education entry
        $education->delete();

        // Return success response confirming deletion
        return response()->json([
            'message' => 'Education entry deleted successfully',
        ], 200);
    }

    /**
     * Retrieve a specific education entry by ID.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewEducation(Request $request, $id)
    {
        // Find the education entry by ID
        $education = Education::find($id);
        if (!$education) {
            // Return error response if education entry not found
            return response()->json(['error' => 'Education not found'], 404);
        }

        // Return success response with the specific education data
        return response()->json([
            'message' => 'Education entry retrieved successfully',
            'data' => $education
        ]);
    }

    /**
     * Validate the education data from the request.
     *
     * @param Request $request
     * @return array
     */
    private function validateEducation(Request $request)
    {
        // Define validation rules for education data
        return $request->validate([
            'level' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'board' => 'required|string|max:255',
            'start_year' => 'required|date_format:Y',
            'end_year' => 'nullable|date_format:Y',
            'description' => 'nullable|string',
        ]);
    }
}
