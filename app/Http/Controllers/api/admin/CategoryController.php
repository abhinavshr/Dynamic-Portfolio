<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Get all categories
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'message' => 'Categories fetched successfully',
            'categories' => $categories
        ]);
    }
}
