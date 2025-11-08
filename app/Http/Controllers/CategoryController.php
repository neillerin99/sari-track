<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Category::query()->where('store_id', $request->store_id)->orWhere('is_global', true);
            if ($request->filled('include')) {
                $includes = explode(',', $request->input('include'));
                $query->with($includes);
            }
            if ($request->has('search')) {
                $query->where('name', 'ilike', "%{$request->search}%");
            }
            $categories = $query->paginate(10)
                ->appends($request->query());
            return ResponseHelper::success($categories, 'Categories fetched!', 200, true);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = Category::create($validated);

            return ResponseHelper::success($category, 'Category created', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ResponseHelper::error('Category not found!', '', 404);
        }

        return ResponseHelper::success($category, 'Category found!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return ResponseHelper::error('Category not found!', '', 404);
            }

            $category->update($request->all());

            return response()->json(['data' => $category], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return ResponseHelper::error('Category not found!', '', 404);
            }

            $category->delete();

            return response()->json(['message' => 'Category deleted', 'data' => $category], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }

    }
}
