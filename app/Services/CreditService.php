<?php

namespace App\Services;

use App\Models\Credit;
use App\Models\CreditItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditService
{
    public function storeData(Request $request, $validated)
    {
        DB::beginTransaction();
        try {
            $credit = Credit::create([
                'store_id' => $validated->store_id,
                'name' => $validated->name,
                'total_price' => $request->total_price,
                'notes' => $request->notes,
                'is_free_form' => $request->is_free_form
            ]);

            if ($request->boolean('is_free_form') === true) {
                DB::commit();
                return (object) [
                    'status' => 'success',
                    'data' => $credit
                ];
            }

            if ($request->items) {
                $items = collect($request->items);
                if ($items->sum('price') != $request->total_price) {
                    DB::rollBack();
                    return (object) [
                        'status' => 'failed',
                        'data' => 'Total price does not match computed price!'
                    ];
                }

                $items->map(function ($item) use ($credit) {
                    $item = (object) $item;
                    CreditItem::create([
                        'credit_id' => $credit->id,
                        'item_id' => $item->item_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ]);
                });
            }

            DB::commit();

            return (object) [
                'status' => 'success',
                'data' => $credit
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}