<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{

    public function _checkLogin(Request $request){
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function storeExperience(Request $request)
    {
        $this->_checkLogin($request);

        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'description' => 'nullable|string',
        ]);

        $experience = Experience::create($validatedData);

        return response()->json(
            ['message' => 'Experience created successfully'
            , 'data' => $experience
        ], 201);
    }

    public function viewAllExperiences(Request $request)
    {
        $this->_checkLogin($request);

        $experiences = Experience::all();
        return response()->json($experiences);
    }

    public function viewOneByOneExperience(Request $request, $id)
    {
        $this->_checkLogin($request);

        $experience = Experience::find($id);
        if (!$experience) {
            return response()->json(['error' => 'Experience not found'], 404);
        }
        return response()->json($experience);
    }

    public function updateExperience(Request $request, $id)
    {
        $this->_checkLogin($request);

        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'description' => 'nullable|string',
        ]);

        $experience = Experience::find($id);

        $experience->update($validatedData);
        return response()->json(
            ['message' => 'Experience updated successfully'
            , 'data' => $experience
        ], 200);
    }

    public function deleteExperience(Request $request, $id)
    {
        $this->_checkLogin($request);

        $experience = Experience::find($id);

        $experience->delete();
        return response()->json(
            ['message' => 'Experience deleted successfully'
        ], 200);
    }
}
