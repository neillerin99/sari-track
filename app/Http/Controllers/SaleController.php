<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Sale::query()->where('store_id', $request->store_id);

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
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(['customer_name' => 'required']);
            $sale = Sale::create($request->all());
            return ResponseHelper::success($sale, 'Sale created', 201);
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
            $sale = Sale::with('items')->find($id);
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
    public function update(Request $request, string $id)
    {
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return ResponseHelper::error(['Sale not found'], 'Sale update failed', 404);
            }
            // TODO: validation
            $sale->update($request->all());
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
