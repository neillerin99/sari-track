<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Credit\CreateCreditRequest;
use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Credit::query();

            if ($request->has('search')) {
                $query->where('name', 'ilike', "%{$request->search}%");
            }

            $credits = $query->where('store_id', $request->store_id)->paginate(10);
            return ResponseHelper::success($credits, 'Credits fetched!', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCreditRequest $request)
    {
        try {
            $validated = (object) $request->validated();
            // Compute total price
            $computed_total = 5;
            // Check if $request->total_price matches computed price
            if ($computed_total != $request->total_price) {
                return ResponseHelper::error('Total price does not match computed price!', 'Error Storing Credit', 400);
            }

            $credit = Credit::create([
                'store_id' => $validated->store_id,
                'name' => $validated->name,
                'total_price' => $request->total_price,
                'notes' => $request->notes,
            ]);
            return ResponseHelper::success($credit, 'Credit created!', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
