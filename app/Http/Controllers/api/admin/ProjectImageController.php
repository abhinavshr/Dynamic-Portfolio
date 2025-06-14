<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class ProjectImageController
 * @package App\Http\Controllers\api\admin
 */
class ProjectImageController extends Controller
{
    /**
     * Middleware to check for admin authentication
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * View all project images
     * @return \Illuminate\Http\JsonResponse
     */
    public function projectImageView()
    {
        return response()->json([
            'message' => 'All project images fetched successfully.',
            'data' => ProjectImage::all()
        ]);
    }

    /**
     * Add a new project image
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProjectImage(Request $request)
    {
        $validated = $this->validateImageRequest($request, true);

        // Store the image
        $filePath = $this->storeImage($request->file('image'));

        // Create the project image
        $projectImage = ProjectImage::create([
            'project_id' => $validated['project_id'],
            'image_path' => $filePath,
        ]);

        // Return the response
        return response()->json([
            'message' => 'Image uploaded successfully',
            'data' => $projectImage,
            'image_url' => asset('storage/' . $filePath),
        ], 201);
    }

    /**
     * Update a project image
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProjectImage(Request $request, $id)
    {
        // Find the project image
        $projectImage = ProjectImage::findOrFail($id);

        // Validate the request
        $validated = $this->validateImageRequest($request, false);

        // Update the project image
        if (isset($validated['project_id'])) {
            $projectImage->project_id = $validated['project_id'];
        }

        // Update the image if it exists
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists($projectImage->image_path);
            $projectImage->image_path = $this->storeImage($request->file('image'));
        }

        // Save the project image
        $projectImage->save();

        // Return the response
        return response()->json([
            'message' => 'Project image updated successfully',
            'data' => $projectImage,
            'image_url' => asset('storage/' . $projectImage->image_path),
        ]);
    }

    /**
     * Delete a project image
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProjectImage($id)
    {
        // Find the project image
        $projectImage = ProjectImage::findOrFail($id);

        // Delete the image
        $this->deleteImageIfExists($projectImage->image_path);

        // Delete the project image
        $projectImage->delete();

        // Return the response
        return response()->json([
            'message' => 'Image and record deleted successfully.'
        ]);
    }

    /**
     * Validate the image request
     * @param Request $request
     * @param bool $isCreate
     * @return array
     */
    private function validateImageRequest(Request $request, $isCreate = true)
    {
        return $request->validate([
            'project_id' => $isCreate ? 'required|exists:projects,id' : 'sometimes|exists:projects,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    /**
     * Store the image
     * @param $file
     * @return mixed
     */
    private function storeImage($file)
    {
        $fileName = Str::slug('project-image-' . time()) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('project_images', $fileName, 'public');
    }

    /**
     * Delete the image if it exists
     * @param $path
     */
    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

