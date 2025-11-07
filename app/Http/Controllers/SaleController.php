<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Sales\CreateSaleRequest;
use App\Http\Requests\Sales\UpdateSaleRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(protected SaleService $sale_service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Sale::query()->where('store_id', $request->store_id)->with('sale_items');

            if ($request->has('search')) {
                $query->where('customer_name', 'ilike', "%{$request->search}%")
                    ->orWhere('transaction_no', 'ilike', "%{$request->search}%");
            }

            $sales = $query->paginate(10)->appends($request->query());
            return ResponseHelper::success($sales, 'Sales fechted', 200, true);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSaleRequest $request)
    {
        try {
            $validated = $request->validated();
            $result = $this->sale_service->storeData($validated, collect($request->items));
            if ($result->status === 'failed') {
                return ResponseHelper::error($result->data, 'Credit store failed', 404);
            }

            if ($result->status === 'success') {
                return ResponseHelper::success(['sale' => $result->data, 'change' => $result->change], 'Credit created!', 201);
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
            $sale = Sale::with('sale_items')->find($id);
            if (!$sale) {
                return ResponseHelper::error(['Sale not found'], 'Sale fetched failed', 404);
            }
            return ResponseHelper::success($sale, 'Sale fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, string $id)
    {
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return ResponseHelper::error(['Sale not found'], 'Sale update failed', 404);
            }
            $validated = $request->validated();
            $sale->update($validated);
            return ResponseHelper::success($sale, 'Sale updated');
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
            $sale = Sale::find($id);
            if (!$sale) {
                return ResponseHelper::error(['Sale not found'], 'Sale fetched failed', 404);
            }
            $sale->delete();
            return ResponseHelper::success($sale, 'Sale deleted');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
