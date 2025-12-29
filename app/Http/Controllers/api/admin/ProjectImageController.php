<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
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
            'data' => ProjectImage::with('project:id,title')->get()
        ]);
    }

    /**
     * Get a project image by ID
     */
    public function showProjectImage($id)
    {
        $projectImage = ProjectImage::with('project:id,title')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Project image fetched successfully.',
            'data'    => $projectImage,
        ]);
    }

    /**
     * Add a new project image
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeProjectImage(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'image_name' => 'required|string',
            'image'      => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $project = Project::findOrFail($request->project_id);

        $folder = 'projects';

        $imageName = Str::slug($request->image_name);

        $upload = Cloudinary::upload(
            $request->file('image')->getRealPath(),
            [
                'folder'    => $folder,
                'public_id' => $imageName,
                'overwrite' => true,
            ]
        );

        $projectImage = ProjectImage::create([
            'project_id' => $project->id,
            'image_name' => $imageName,
            'image_path' => $upload->getSecurePath(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully.',
            'data'    => $projectImage,
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
        $request->validate([
            'image_name' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'image'      => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $projectImage = ProjectImage::findOrFail($id);

        $newImageName = Str::slug($request->image_name);

        if ($request->hasFile('image')) {
            $oldPublicId = 'projects/' . $projectImage->image_name;

            Cloudinary::destroy($oldPublicId);

            $upload = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                [
                    'folder'    => 'projects',
                    'public_id' => $newImageName,
                    'overwrite' => true,
                ]
            );

            $projectImage->update([
                'project_id' => $request->project_id,
                'image_name' => $newImageName,
                'image_path' => $upload->getSecurePath(),
            ]);
        } else {
            $projectImage->update([
                'project_id' => $request->project_id,
                'image_name' => $newImageName,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Project image updated successfully.',
            'data'    => $projectImage,
        ]);
    }

    /**
     * Delete a project image
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProjectImage($id)
    {
        $projectImage = ProjectImage::with('project')->findOrFail($id);

        // Build Cloudinary public_id
        $publicId = 'projects/' .
            Str::slug($projectImage->project->title) .
            '/' .
            $projectImage->image_name;

        // Delete from Cloudinary
        Cloudinary::destroy($publicId);

        // Delete DB record
        $projectImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted from Cloudinary and database successfully.',
        ]);
    }
}
