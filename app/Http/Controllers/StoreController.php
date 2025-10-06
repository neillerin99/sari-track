<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CreateStoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Store::with('user')->where('status', '=', 'active');

        if ($request->filled('search')) {
            $query->where('name', 'ilike', "%{$request->search}%");
        }

        $item = $query->paginate(10)->appends($request->query());
        return response()->json($item, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = Auth::user();

            $store = Store::create([
                ...$validated,
                'user_id' => $user->id,
            ]);
            return ResponseHelper::success($store, 'Store created!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = Store::find($id);

        if (!$store) {
            return ResponseHelper::error([], 'Store not found!', 404);
        }

        return ResponseHelper::success($store, 'Item found!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $store = Store::find($id);

            if (!$store) {
                return ResponseHelper::error([], 'Store not found!', 404);
            }

            $store->update($request->all());
            return ResponseHelper::success($store, 'Item updated!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $store = Store::find($id);

            if (!$store) {
                return ResponseHelper::error([], 'Store not found!', 404);
            }

            $store->delete();

            return ResponseHelper::success($store, 'Store deleted!');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th, 'Server Error', 500);
        }

    }
}
