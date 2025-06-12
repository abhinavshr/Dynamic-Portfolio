<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function storeSkill(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:0|max:100',
            'category' => 'required|string|max:255',
        ]);

        $skill = Skill::create($validated);
        return response()->json($skill, 201);
    }

    public function viewAllSkills()
    {
        $skills = Skill::all();
        return response()->json($skills);
    }

    public function updateSkill(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:0|max:100',
            'category' => 'required|string|max:255',
        ]);

        $skill = Skill::findOrFail($id);
        $skill->update($validated);
        return response()->json($skill);
    }

    public function deleteSkill($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();
        return response()->json([
            'message' => 'Skill deleted successfully.',
        ]);
    }
}
