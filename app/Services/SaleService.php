<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class SaleService
{
    public function storeData($validated, $items): object
    {
        $sub_total = $items->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        if ($sub_total !== $validated['total_amount']) {
            return (object) [
                'status' => 'failed',
                'data' => 'Total price does not match computed price!'
            ];
        }

        DB::beginTransaction();
        try {
            $transaction_no = $this->generateTransactionNumber($validated['store_id']);
            $sale = Sale::create([...$validated, 'transaction_no' => $transaction_no]);

            if ($items) {
                $items->each(function ($item) use ($sale) {
                    $item = (object) $item;
                    info(json_encode($item));
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'item_id' => $item->item_id ?? NULL,
                        'name' => $item->name ?? NULL,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->price * $item->quantity,
                        'is_manual' => $item->is_manual ?? false
                    ]);
                });
            }
            DB::commit();
            return (object) [
                'status' => 'success',
                'data' => $sale
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return (object) [
                'status' => 'failed',
                'data' => $e->getMessage()
            ];
        }
    }

    private function generateTransactionNumber(string $store_id): string
    {
        $store = Store::find($store_id);
        if (!$store) {
            throw new Exception("Store not found");
        }
        $acronym = collect(explode(' ', $store->name))
            ->map(fn($w) => Str::upper(Str::substr($w, 0, 1)))
            ->implode('');
        $date = Carbon::now();
        $sale_count = (int) Sale::where('store_id', $store_id)->count() + 1;
        return "{$acronym}-{$date->format('Ymd')}-{$sale_count}";
    }
}