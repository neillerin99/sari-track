<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * BelongsToMany: Items
     * 
     * Returns items linked to this sale that already exist in the `items` table.
     * Excludes manually entered items that aren’t stored in `items`.
     */
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

    /**
     * HasMany: Sale Items
     * 
     * Returns all sale item entries (both linked and manually entered),
     * including those not stored in the `items` table.
     */
    public function sale_items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
