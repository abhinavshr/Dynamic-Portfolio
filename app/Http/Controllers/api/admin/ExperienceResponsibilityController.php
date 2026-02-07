<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ExperienceResponsibility;
use Illuminate\Http\Request;

class ExperienceResponsibilityController extends Controller
{
    public function addResponsibility(Request $request, $experienceId)
    {
        $count = ExperienceResponsibility::where('experience_id', $experienceId)->count();

        if ($count >= 5) {
            return response()->json([
                'message' => 'Maximum 5 responsibilities allowed'
            ], 400);
        }

        ExperienceResponsibility::create([
            'experience_id' => $experienceId,
            'responsibility' => $request->responsibility
        ]);

        return response()->json(['message' => 'Added successfully']);
    }

    public function getAllResponsibilities()
    {
        $responsibilities = ExperienceResponsibility::with('experience')->get();

        return response()->json($responsibilities);
    }

    public function getResponsibilitiesByExperience(Experience $experience)
    {
        return response()->json(
            $experience->responsibilities()->get()
        );
    }
}
