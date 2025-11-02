<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restock extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'status',
        'notes',
        'buy_date'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'restock_items')
            ->withPivot('name', 'quantity', 'checked', 'notes')
            ->as('restock_items')
            ->withTimestamps();
    }
}
