<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function storeEducation(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'level' => 'required',
            'program' => 'required',
            'institution' => 'required',
            'board' => 'required',
            'start_year' => 'required|date_format:Y',
            'end_year' => 'nullable|date_format:Y',
            'description' => 'nullable',
        ]);

        $education = Education::create($validatedData);

        return response()->json($education, 201);
    }

    public function viewAllEducations(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $educations = Education::all();
        return response()->json($educations);
    }

    public function updateEducation(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $education = Education::find($id);
        if (!$education) {
            return response()->json(['error' => 'Education not found'], 404);
        }

        $validatedData = $request->validate([
            'level' => 'required',
            'program' => 'required',
            'institution' => 'required',
            'board' => 'required',
            'start_year' => 'required|date_format:Y',
            'end_year' => 'nullable|date_format:Y',
            'description' => 'nullable',
        ]);

        $education->update($validatedData);

        return response()->json([
            'message' => 'Education updated successfully',
            'data' => $education
        ], 200);
    }

    public function deleteEducation(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $education = Education::find($id);
        if (!$education) {
            return response()->json(['error' => 'Education not found'], 404);
        }

        $education->delete();

        return response()->json([
            'message' => 'Education deleted successfully',
        ], 200);
    }

    public function viewEducation(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $education = Education::find($id);
        if (!$education) {
            return response()->json(['error' => 'Education not found'], 404);
        }

        return response()->json($education);
    }
}
