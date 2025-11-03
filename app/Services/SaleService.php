<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Store;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Str;

class SaleService
{
    public function storeData($validated): object
    {
        DB::beginTransaction();
        try {
            $transaction_no = $this->generateTransactionNumber($validated['store_id']);
            $sale = Sale::create([...$validated, 'transaction_no' => $transaction_no]);
            // TODO: add logic for sales item creation
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