<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Batches\CreateBatchRequest;
use App\Http\Requests\Batches\UpdateBatchRequest;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Batch::where('item_id', $request->item_id)->orderBy('batch_number');
            if ($request->filled('filter')) {
                $query->where('status', $request->filter);
            }
            $batches = $query->paginate(10)->appends($request->query());
            return ResponseHelper::success($batches, 'Batches fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBatchRequest $request)
    {
        try {
            $validated = $request->validated();
            $batch_count = Batch::where('item_id', $request->item_id)->max('batch_number') ?? 0;
            $batch = Batch::create([
                ...$validated,
                'batch_number' => $batch_count + 1,
                'received_at' => Carbon::now()
            ]);
            return ResponseHelper::success($batch, 'Item batch created', 201);
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
            $batch = Batch::with('item')->find($id);
            if (!$batch) {
                return ResponseHelper::error(['Batch not found!'], 'Batch feteched failed!', 404);
            }
            return ResponseHelper::success($batch, 'Batch fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBatchRequest $request, string $id)
    {
        try {
            $batch = Batch::find($id);
            if (!$batch) {
                return ResponseHelper::error(['Batch not found!'], 'Batch feteched failed!', 404);
            }
            $validated = $request->validated();
            $batch->update($validated);
            return ResponseHelper::success($batch, 'Batch updated');
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
            $batch = Batch::find($id);
            if (!$batch) {
                return ResponseHelper::error(['Batch not found!'], 'Batch delete failed!', 404);
            }
            $batch->delete();
            return ResponseHelper::success($batch, 'Batch deleted');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
