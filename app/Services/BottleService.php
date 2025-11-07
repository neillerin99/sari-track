<?php

namespace App\Services;

use App\Models\Bottle;
use App\Models\BottleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BottleService
{
    public function storeData(Request $request, object $validated)
    {

        DB::beginTransaction();
        try {
            $bottle = Bottle::create([
                'store_id' => $validated->store_id,
                'name' => $validated->name,
                'price' => $validated->price,
                'notes' => $validated->notes,
                'is_free_form' => $request->is_free_form ?? false
            ]);
            if ($request->boolean('is_free_form') === true) {
                return (object) [
                    'status' => 'success',
                    'data' => $bottle
                ];
            }
            if ($request->items) {
                $items = collect($request->items);
                $items->each(function ($item) use ($bottle) {
                    $item = (object) $item;
                    BottleItem::create([
                        'bottle_id' => $bottle->id,
                        'item_id' => $item->item_id,
                        'quantity' => $item->quantity
                    ]);
                });
            }
            DB::commit();
            return (object) [
                'status' => 'success',
                'data' => $bottle
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}