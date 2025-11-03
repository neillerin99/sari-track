<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'store_id',
        'transaction_no',
        'status',
        'customer_name',
        'total_amount',
        'paid_amount',
        'notes',
        'is_fully_paid',
        'payment_method'
    ];


    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'sale_items')
            ->withPivot(
                'name',
                'price',
                'quantity',
                'subtotal',
                'is_manual'
            )
            ->as('sale_items')
            ->withTimestamps();
    }
}
