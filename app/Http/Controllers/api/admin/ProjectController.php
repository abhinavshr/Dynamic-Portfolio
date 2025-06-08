<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function addProject(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'tech_stack' => 'required|string|max:255',
                'live_link' => 'nullable|url',
                'github_link' => 'nullable|url',
            ]);

            $project = new Project($validatedData);
            $project->save();

            return response()->json([
                'message' => 'Project added successfully',
                'project' => $project
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function viewAllProjects(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $projects = Project::all();

            return response()->json([
                'message' => 'Projects fetched successfully',
                'projects' => $projects
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProject(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $project = Project::find($id);

            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'tech_stack' => 'required|string|max:255',
                'live_link' => 'nullable|url',
                'github_link' => 'nullable|url',
            ]);

            $project->update($validatedData);

            return response()->json([
                'message' => 'Project updated successfully',
                'project' => $project
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteProject(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $project = Project::find($id);

            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            $project->delete();

            return response()->json([
                'message' => 'Project deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
