<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Items\CreateItemRequest;
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
        try {
            $query = Item::with('category')
                ->where('is_active', '=', true)
                ->where('store_id', $request->store_id)
                ->with('batches');

            if ($request->search) {
                if (!$request->search_by || $request->search_by === 'item') {
                    $query->where('name', 'ilike', "%{$request->search}%");
                } else {
                    $query->whereHas('category', function ($q) use ($request) {
                        $q->where('name', 'ilike', "%{$request->search}%");
                    });
                }
            }
            $items = $query->paginate(10);
            return ResponseHelper::success($items, 'Items fetched!', 200, true);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateItemRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->has('category_id')) {
                $category = Category::find($validated['category_id']);

                if ($category->is_active == false) {
                    return response()->json(['message' => 'Category is currently inactive.'], 400);
                }
            }

            $item = Item::create($validated);
            return ResponseHelper::success($item, 'Item created!', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::with('category', 'batches')->find($id);

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
        try {
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
            $item = Item::find($id);

            if (!$item) {
                return ResponseHelper::error('Item not found', '', 404);
            }

            $item->delete();

            return ResponseHelper::success($item, 'Item deleted');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
