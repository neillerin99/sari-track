<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Credit;
use App\Models\CreditItem;
use DB;
use Illuminate\Http\Request;

class CreditService
{
    public function process(Request $request, $validated)
    {
        $result = DB::transaction(function () use ($request, $validated) {
            $credit = Credit::create([
                'store_id' => $validated->store_id,
                'name' => $validated->name,
                'total_price' => $request->total_price,
                'notes' => $request->notes,
            ]);

            if ($request->items) {
                foreach ($request->items as $item) {
                    $item = (object) $item;
                    CreditItem::create([
                        'credit_id' => $credit->id,
                        'item_id' => $item->item_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ]);
                }
            }

            return (object) [
                'status' => 'success',
                'data' => $credit
            ];
        });

        return $result;

    }
}