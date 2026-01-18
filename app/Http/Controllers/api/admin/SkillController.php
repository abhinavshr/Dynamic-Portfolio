<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Skill;
use App\Models\SoftSkill;
use Illuminate\Http\Request;

/**
 * Class SkillController
 * @package App\Http\Controllers\api\admin
 */
class SkillController extends Controller
{
    /**
     * Apply middleware for authentication with admin guard.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a new skill in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSkill(Request $request)
    {
        $validated = $this->validateSkill($request);

        $skill = Skill::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully.',
            'data' => $skill->load('category'),
        ], 201);
    }


    /**
     * Display a listing of all skills.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAllSkills(Request $request)
{
    $categories = Category::whereHas('skills')
        ->with(['skills' => function ($query) {
            $query->with('category')->limit(6);
        }])
        ->paginate(3);

    return response()->json([
        'success' => true,
        'message' => 'Skills retrieved successfully.',
        'data' => $categories
    ]);
}

    /**
     * Retrieve a specific skill by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewSkillById($id)
    {
        // Retrieve the skill with its category
        $skill = Skill::with('category')->find($id);

        if (!$skill) {
            return response()->json([
                'success' => false,
                'message' => 'Skill not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Skill retrieved successfully.',
            'data' => $skill,
        ]);
    }

    /**
     * Update the specified skill in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSkill(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $this->validateSkill($request);

        $skill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully.',
            'data' => $skill->load('category'),
        ]);
    }


    /**
     * Remove the specified skill from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSkill($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully.',
        ]);
    }

    /**
     * Retrieve the total number of skills and soft skills.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalSkills()
    {
        $totalSkills = Skill::count();
        $totalSoftSkills = SoftSkill::count();

        return response()->json([
            'success' => true,
            'message' => 'Total skills fetched successfully.',
            'data' => [
                'total_skills' => $totalSkills,
                'total_soft_skills' => $totalSoftSkills,
                'grand_total' => $totalSkills + $totalSoftSkills
            ]
        ]);
    }


    /**
     * Validate the request data for a skill.
     *
     * @param Request $request
     * @return array
     */
    private function validateSkill(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
        ]);
    }
}
