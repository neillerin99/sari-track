<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Credit\CreateCreditRequest;
use App\Models\Credit;
use App\Services\CreditService;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function __construct(private CreditService $credit_service)
    {

    }

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
            $result = $this->credit_service->process($request, $validated);

            if ($result->status === 'failed') {
                return ResponseHelper::error($result->data, 'Credit store failed', 404);
            }

            if ($result->status === 'success') {
                return ResponseHelper::success($result, 'Credit created!', 201);
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
            $credit = Credit::with('items')->find($id);
            if (!$credit) {
                return ResponseHelper::error(['Credit not found!'], 'Credit fetch failed', 404);
            }
            return ResponseHelper::success($credit, 'Credit found!');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
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
        try {
            $credit = Credit::find($id);
            if (!$credit) {
                return ResponseHelper::error(['Credit not found!'], 'Credit deletion failed', 404);
            }
            $credit->delete();
            return ResponseHelper::success($credit, 'Store deleted!');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
