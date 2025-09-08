<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::with('category')
            ->where('is_active', '=', true);

        if ($request->search) {
            if (!$request->search_by || $request->search_by === 'item') {
                $query->where('name', 'ilike', "%{$request->search}%");
            } else {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('name', 'ilike', "%{$request->search}%");
                });
            }
        }
        $item = $query->paginate(10);
        return response()->json($item, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'uuid|exists:categories,id',
            'unit' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:50|unique:items,barcode',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($request->has('category_id')) {
            $category = Category::find($validated['category_id']);

            if ($category->is_active == false) {
                return response()->json(['message' => 'Category is currently inactive.'], 400);
            }
        }

        $item = Item::create($validated);
        return ResponseHelper::success($item, 'Item created!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::with('category')->find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found!'], 404);
        }

        return ResponseHelper::success($item, 'Item found!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found!'], 404);
        }

        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);

            if (!$category) {
                return ResponseHelper::error('Category not found', '', 400);
            }

            if ($category->is_active == false) {
                return ResponseHelper::error('Category is currently inactive.', '', 400);
            }
        }

        $item->update($request->all());
        return ResponseHelper::success($item, 'Item updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return ResponseHelper::error('Item not found', '', 404);
        }

        $item->delete();

        return ResponseHelper::success($item, 'Item deleted');
    }
}
