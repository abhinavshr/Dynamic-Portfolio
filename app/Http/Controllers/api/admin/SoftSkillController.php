<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\SoftSkill;
use Illuminate\Http\Request;

/**
 * Class SoftSkillController
 * @package App\Http\Controllers\api\admin
 *
 * This controller handles the requests related to soft skills.
 * It provides methods to create, retrieve, update and delete a soft skill.
 */
class SoftSkillController extends Controller
{
    /**
     * Apply middleware to ensure the user is an authenticated admin.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a new soft skill in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSoftSkill(Request $request)
    {
        $validated = $this->validateSoftSkill($request);

        $softskill = SoftSkill::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Soft skill created successfully.',
            'data' => $softskill
        ], 201);
    }

    /**
     * Retrieve all soft skills from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAllSoftSkill(Request $request)
    {
        $softskills = SoftSkill::paginate(8);

        return response()->json([
            'success'    => true,
            'message'    => 'Soft skills retrieved successfully.',
            'data'       => $softskills->items(),
            'pagination' => [
                'total'        => $softskills->total(),
                'per_page'     => $softskills->perPage(),
                'current_page' => $softskills->currentPage(),
                'last_page'    => $softskills->lastPage(),
                'next_page_url' => $softskills->nextPageUrl(),
                'prev_page_url' => $softskills->previousPageUrl(),
            ]
        ]);
    }

    /**
     * Retrieve a specific soft skill by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewOneByOneSoftSkill($id)
    {
        $softskill = SoftSkill::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Soft skill retrieved successfully.',
            'data' => $softskill
        ]);
    }

    /**
     * Update a soft skill in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSoftSkill(Request $request, $id)
    {
        $softskill = SoftSkill::findOrFail($id);
        $validated = $this->validateSoftSkill($request);

        $softskill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Soft skill updated successfully.',
            'data' => $softskill
        ]);
    }

    /**
     * Remove a soft skill from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSoftSkill($id)
    {
        $softskill = SoftSkill::findOrFail($id);
        $softskill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soft skill deleted successfully.'
        ]);
    }

    /**
     * Validate the request data for a soft skill.
     *
     * @param Request $request
     * @return array
     */
    private function validateSoftSkill(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:100',
        ]);
    }
}
