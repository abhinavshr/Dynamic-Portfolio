<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ExperienceResponsibility;
use Illuminate\Http\Request;

class ExperienceResponsibilityController extends Controller
{
    /**
     * Add multiple responsibilities to an experience.
     *
     * @param Request $request
     * @param int $experienceId
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addResponsibility(Request $request, $experienceId)
    {
        $request->validate([
            'responsibilities' => 'required|array|min:1|max:5',
            'responsibilities.*' => 'required|string|max:255',
        ]);

        $existingCount = ExperienceResponsibility::where('experience_id', $experienceId)->count();
        $newCount = count($request->responsibilities);

        if (($existingCount + $newCount) > 5) {
            return response()->json([
                'message' => 'You can only have a maximum of 5 responsibilities per experience'
            ], 400);
        }

        foreach ($request->responsibilities as $item) {
            ExperienceResponsibility::create([
                'experience_id' => $experienceId,
                'responsibility' => $item
            ]);
        }

        return response()->json([
            'message' => 'Responsibilities added successfully'
        ], 201);
    }

    /**
     * Get all responsibilities with their corresponding experiences.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllResponsibilities()
    {
        $responsibilities = ExperienceResponsibility::with('experience')->get();

        return response()->json($responsibilities);
    }

    /**
     * Get all responsibilities associated with the given experience.
     *
     * @param Experience $experience The experience to retrieve the responsibilities for.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResponsibilitiesByExperience(Experience $experience)
    {
        return response()->json(
            $experience->responsibilities()->get()
        );
    }

    /**
     * Update an existing responsibility.
     *
     * @param Request $request The request containing the data to update the responsibility with.
     * @param ExperienceResponsibility $responsibility The responsibility to update.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateResponsibility(Request $request, ExperienceResponsibility $responsibility)
    {
        $request->validate([
            'responsibility' => 'required|string|max:255',
        ]);

        $responsibility->update([
            'responsibility' => $request->responsibility
        ]);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $responsibility
        ]);
    }

    /**
     * Delete an existing responsibility.
     *
     * @param ExperienceResponsibility $responsibility The responsibility to delete.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteResponsibility(ExperienceResponsibility $responsibility)
    {
        $responsibility->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
