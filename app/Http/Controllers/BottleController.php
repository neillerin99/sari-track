<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Bottles\CreateBottleRequest;
use App\Http\Requests\Bottles\UpdateBottleRequest;
use App\Models\Bottle;
use App\Services\BottleService;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class BottleController extends Controller
{

    public function __construct(protected BottleService $bottle_service)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Bottle::query()->where('store_id', $request->store_id);
            if ($request->filled('search')) {
                $query->where('name', 'ilike', "%{$request->search}%");
            }
            $bottles = $query->paginate(10)->appends($request->query());
            return ResponseHelper::success($bottles, 'Bottles fetched', 200, true);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), "Server error", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBottleRequest $request)
    {
        try {
            $validated = (object) $request->validated();
            $result = $this->bottle_service->store_data($request, $validated);

            if ($result->status === 'failed') {
                return ResponseHelper::error($result->data, 'Credit store failed', 404);
            }
            if ($result->status === 'success') {
                return ResponseHelper::success($result->data, 'Credit created!', 201);
            }

        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $bottle = Bottle::with('items')->find($id);
            if (!$bottle) {
                return ResponseHelper::error(['Bottle not found!'], 'Bottle fetched failed', 404);
            }

            return ResponseHelper::success($bottle, 'Bottle found');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), "Server error", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBottleRequest $request, string $id)
    {
        try {
            $bottle = Bottle::find($id);
            if (!$bottle) {
                return ResponseHelper::error(['Bottle not found'], 'Bottle update failed', 404);
            }
            $validated = $request->validated();
            $bottle->update($validated);
            return ResponseHelper::success($bottle, 'Bottle updated!');
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
            $bottle = Bottle::find($id);
            if (!$bottle) {
                return ResponseHelper::error(['Bottle not found!'], 'Bottle fetched failed', 404);
            }
            $bottle->delete();
            return ResponseHelper::success($bottle, 'Bottle deleted!');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), "Server Error", 500);
        }
    }
}
