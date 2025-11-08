<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BottleItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'bottle_id',
        'item_id',
        'quantity'
    ];

    public function bottle(): BelongsTo
    {
        return $this->belongsTo(Bottle::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
