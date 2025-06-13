<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\SoftSkill;
use Illuminate\Http\Request;

class SoftSkillController extends Controller
{

    public function _checkLogin(Request $request){
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function storeSoftSkill(Request $request)
    {
        $this->_checkLogin($request);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|integer',
        ]);

        $softskill = SoftSkill::create($validatedData);

        return response()->json([
            'message' => 'SoftSkill created successfully', 'data' => $softskill
        ], 201);
    }

    public function viewAllSoftSkill(Request $request)
    {
        $this->_checkLogin($request);

        $softskills = SoftSkill::all();
        return response()->json($softskills, 200);
    }

    public function viewOneByOneSoftSkill(Request $request, $id)
    {
        $this->_checkLogin($request);

        $softskill = SoftSkill::find($id);

        if (!$softskill) {
            return response()->json(['error' => 'SoftSkill not found'], 404);
        }

        return response()->json($softskill, 200);
    }

    public function updateSoftSkill(Request $request, $id)
    {
        $this->_checkLogin($request);

        $softskill = SoftSkill::find($id);

        if (!$softskill) {
            return response()->json(['error' => 'SoftSkill not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|integer',
        ]);

        $softskill->update($validatedData);

        return response()->json([
            'message' => 'SoftSkill updated successfully', 'data' => $softskill
        ], 200);
    }

    public function deleteSoftSkill(Request $request, $id)
    {
        $this->_checkLogin($request);

        $softskill = SoftSkill::find($id);

        if (!$softskill) {
            return response()->json(['error' => 'SoftSkill not found'], 404);
        }

        $softskill->delete();

        return response()->json([
            'message' => 'SoftSkill deleted successfully',
        ], 200);
    }
}
