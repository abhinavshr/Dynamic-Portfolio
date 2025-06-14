<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Apply middleware to ensure the user is an authenticated admin.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Add a new project to the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProject(Request $request)
    {
        $validatedData = $this->validateProject($request);

        $project = Project::create($validatedData);

        return response()->json([
            'message' => 'Project added successfully',
            'project' => $project
        ], 201);
    }

    /**
     * Retrieve all projects from the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewAllProjects(Request $request)
    {
        $projects = Project::all();

        return response()->json([
            'message' => 'Projects fetched successfully',
            'projects' => $projects
        ]);
    }

    /**
     * Update an existing project in the database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $validatedData = $this->validateProject($request);

        $project->update($validatedData);

        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project
        ]);
    }

    /**
     * Delete an existing project from the database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    /**
     * Validate the project data from the request.
     *
     * @param Request $request
     * @return array
     */
    private function validateProject(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tech_stack' => 'required|string|max:255',
            'live_link' => 'nullable|url',
            'github_link' => 'nullable|url',
        ]);
    }
}

