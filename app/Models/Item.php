<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'category_id',
        'unit',
        'barcode',
        'description',
        'cost_price',
        'selling_price',
        'is_active',
        'store_id'
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Credit::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function bottles(): BelongsToMany
    {
        return $this->belongsToMany(Bottle::class, 'bottle_items')
            ->withPivot('quantity')
            ->as('item_bottles')
            ->withTimestamps();
    }

    public function restocks(): BelongsToMany
    {
        return $this->belongsToMany(Restock::class)
            ->withPivot('name', 'quantity')
            ->as('restock_items')
            ->withTimestamps();
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'sale_items')
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

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
