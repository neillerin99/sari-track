<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Batch;
use Exception;

class BatchService
{
    public function processBatchSale($item_id, int $sale_quantity)
    {
        $batch = Batch::where('item_id', '=', $item_id)
            ->whereIn('status', ['active', 'low_stock'])
            ->orderBy('batch_number', 'ASC')
            ->first();
        if ($batch) {
            throw new Exception('Batch not found!');
        }
        $quantity = $batch->quantity - $sale_quantity;
        $batch->update(['quantity' => $quantity, 'status' => $this->setStatus($quantity, $batch->status)]);
    }
    private function setStatus($quantity, $status): string
    {
        if ($quantity == 0) {
            return 'sold_out';
        } else if ($quantity < 5) {
            return 'low_stock';
        } else {
            return $status;
        }
    }
}