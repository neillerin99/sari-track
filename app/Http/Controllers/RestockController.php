<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Restock\CreateRestockRequest;
use App\Http\Requests\Restock\UpdateRestockRequest;
use App\Models\Restock;
use App\Services\RestockService;
use Illuminate\Http\Request;

class RestockController extends Controller
{

    public function __construct(protected RestockService $restock_service)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Restock::query()->where('store_id', $request->store_id);
            if ($request->has('search')) {
                $query->where('name', 'ilike', "%{$request->search}%");
            }
            $restocks = $query->paginate(10)->appends($request->query());
            return ResponseHelper::success($restocks, 'Restocks fetched', 200, true);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRestockRequest $request)
    {
        try {
            $validated = $request->validated();
            $result = $this->restock_service->store_data($validated, $request->items);
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
            $restock = Restock::with('items')->find($id);
            if (!$restock) {
                return ResponseHelper::error(['Restock not found!'], 'Restock fetch failed!', 404);
            }
            return ResponseHelper::success($restock, 'Restock fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestockRequest $request, string $id)
    {
        try {
            $restock = Restock::find($id);
            if (!$restock) {
                return ResponseHelper::error(['Restock not found!'], 'Restock update failed!', 404);
            }
            $validated = $request->validated();
            $restock->update($validated);
            return ResponseHelper::success($restock, 'Restock updated', 200);
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
            $restock = Restock::find($id);
            if (!$restock) {
                return ResponseHelper::error(['Restock not found!'], 'Restock fetch failed!', 404);
            }
            $restock->delete();
            return ResponseHelper::success($restock, 'Restock deleted!');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
