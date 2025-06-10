<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectImageController extends Controller
{
    public function projectImageView()
    {
        return response()->json(ProjectImage::all());
    }

    public function addProjectImage(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug('project-image-' . time()) . '.' . $extension;
            $file->storeAs('public/project_images', $fileName);
            $filePath = 'project_images/' . $fileName;
        } else {
            return response()->json(['error' => 'No image file uploaded'], 400);
        }

        $projectImage = ProjectImage::create([
            'project_id' => $request->project_id,
            'image_path' => $filePath,
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'data' => $projectImage,
            'image_url' => asset('storage/' . $filePath),
        ], 201);
    }

// public function updateProjectImage(Request $request, $id)
// {
//     try {
//         $projectImage = ProjectImage::findOrFail($id);

//         try {
//             $request->validate([
//                 'project_id' => 'sometimes|exists:projects,id',
//                 'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//             ]);
//         } catch (ValidationException $e) {
//             return response()->json(['errors' => $e->errors()], 422);
//         }

//         Log::info('All input:', $request->all());
//         Log::info('project_id present:', ['filled' => $request->filled('project_id')]);
//         Log::info('Has file image:', ['hasFile' => $request->hasFile('image')]);

//         $updated = false;

//         if ($request->filled('project_id')) {
//             $projectImage->project_id = $request->project_id;
//             $updated = true;
//         }

//         if ($request->hasFile('image')) {
//             if (!empty($projectImage->image)) {
//                 $oldPath = 'public/' . $projectImage->image;
//                 if (Storage::exists($oldPath)) {
//                     Storage::delete($oldPath);
//                 }
//             }

//             $file = $request->file('image');
//             $extension = $file->getClientOriginalExtension();
//             $fileName = Str::slug('project-image-' . time()) . '.' . $extension;
//             $file->storeAs('public/project_images', $fileName);
//             $projectImage->image = 'project_images/' . $fileName;
//             $updated = true;
//         }

//         if (!$updated) {
//             return response()->json(['error' => 'No data provided to update'], 422);
//         }

//         $projectImage->save();

//         return response()->json([
//             'message' => 'Image updated successfully.',
//             'data' => $projectImage,
//             'image_url' => asset('storage/' . $projectImage->image),
//         ]);
//     } catch (ModelNotFoundException $e) {
//         return response()->json(['error' => 'Project image not found'], 404);
//     } catch (\Exception $e) {
//         Log::error('Update project image error: ' . $e->getMessage());
//         return response()->json(['error' => 'Internal Server Error'], 500);
//     }
// }
public function updateProjectImage(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $projectImage = ProjectImage::findOrFail($id);

        if ($request->has('project_id')) {
            $projectImage->project_id = $request->input('project_id');
        }

        if ($request->hasFile('image')) {
            if ($projectImage->image_path && Storage::disk('public')->exists($projectImage->image_path)) {
                Storage::disk('public')->delete($projectImage->image_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug('project-image-' . time()) . '.' . $extension;
            $filePath = $file->storeAs('project_images', $fileName, 'public'); // This line was already correct
            // $filePath = 'project_images/' . $fileName; // This line is not needed if storeAs returns the full relative path

            $projectImage->image_path = $filePath;
        }

        $projectImage->save();

        return response()->json([
            'message' => 'Project image updated successfully',
            'data' => $projectImage,
            'image_url' => asset('storage/' . $projectImage->image_path),
        ], 200);
    }
    public function deleteProjectImage($id)
    {
        $projectImage = ProjectImage::findOrFail($id);

        if ($projectImage->image_path && Storage::disk('public')->exists($projectImage->image_path)) {
            Storage::disk('public')->delete($projectImage->image_path);
        }

        $projectImage->delete();

        return response()->json([
            'message' => 'Image and record deleted successfully.',
        ]);
    }
}
