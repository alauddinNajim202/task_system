<?php

namespace App\Http\Controllers\api\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Find the category by ID
            $category = Category::all();
    
            // Return the category data
            return response()->json([
                'success' => true,
                'data' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories: ' . $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        //store  category data in  database
        $category = Category::create([
            'name' => $request->name,
        ]);
        //return success message
        return response()->json([
            'success' => true,
            'message' => 'Category Create successfully',
            'token' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);
    
            // Return the category data
            return response()->json([
                'success' => true,
                'data' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to show category: ' . $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            $category->update([
                'name' => $request->name,
            ]);
    
            // Return category data
            return response()->json([
                'success' => true,
                'data' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            //delete the category
            $category->delete();
    
            // Return success message
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $th->getMessage(),
            ], 500);
        }
    }
}
