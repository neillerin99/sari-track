<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestockItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'restock_id',
        'item_id',
        'name',
        'quantity',
        'checked',
        'notes'
    ];

    public function restock(): BelongsTo
    {
        return $this->belongsTo(Restock::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
