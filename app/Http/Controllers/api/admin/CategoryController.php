<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class CategoryController
 * @package App\Http\Controllers\api\admin
 *
 * Handles management of categories for projects and skills.
 */
class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     * Enforces admin authentication.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Fetch all categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::orderBy('name', 'asc')->get();

            return response()->json([
                'success'    => true,
                'message'    => 'Categories fetched successfully',
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Category Index Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch categories.', 500);
        }
    }

    /**
     * Store a newly created category.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 422);
            }

            $category = Category::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Category created successfully',
                'category' => $category
            ], 201);
        } catch (\Exception $e) {
            Log::error('Category Store Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while creating the category.', 500);
        }
    }

    /**
     * Display the specified category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->errorResponse('Category not found.', 404);
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Category fetched successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            Log::error('Category Show Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch category details.', 500);
        }
    }

    /**
     * Update the specified category.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->errorResponse('Category not found.', 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 422);
            }

            $category->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Category updated successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while updating the category.', 500);
        }
    }

    /**
     * Remove the specified category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->errorResponse('Category not found.', 404);
            }

            // Check if category is in use
            if ($category->projects()->exists() || $category->skills()->exists()) {
                return $this->errorResponse('Cannot delete category because it is associated with projects or skills.', 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Category Destroy Error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while deleting the category.', 500);
        }
    }

    /**
     * Get total number of categories.
     *
     * @return JsonResponse
     */
    public function totalCategories(): JsonResponse
    {
        try {
            $count = Category::count();
            return response()->json([
                'success' => true,
                'count'   => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Total Categories Count Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch total categories count.', 500);
        }
    }

    /**
     * Standard error response structure.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
