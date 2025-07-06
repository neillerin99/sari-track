<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Item::paginate(10), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:50|unique:items,barcode',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'cost_price' => 'required|numeric|min:0|max:99999.99',
            'selling_price' => 'required|numeric|min:0|max:99999.99',
        ]);

        $item = Item::create($validated);

        return response()->json([
            'message' => 'Item created!',
            'data' => $item
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found!'], 404);
        }

        return response()->json(['data' => $item], 200);
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

        $item->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found!'], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Item deleted',
            'data' => $item
        ], 200);
    }
}
