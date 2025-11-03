<?php

namespace App\Services;

use App\Models\Restock;
use App\Models\RestockItem;
use Illuminate\Support\Facades\DB;

class RestockService
{
    public function storeData(array $validated, $items)
    {
        DB::beginTransaction();
        try {
            $restock = Restock::create($validated);
            if ($items) {
                $items = collect($items);
                $items->each(function ($item) use ($restock) {
                    $item = (object) $item;
                    info(json_encode($item));
                    RestockItem::create([
                        'restock_id' => $restock->id,
                        'item_id' => $item->item_id ?? NULL,
                        'name' => $item->name ?? NULL,
                        'quantity' => $item->quantity,
                        'notes' => $item->notes ?? NULL
                    ]);
                });
            }
            DB::commit();
            return (object) [
                'status' => 'success',
                'data' => $restock
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}