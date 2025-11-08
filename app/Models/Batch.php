<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'item_id',
        'quantity',
        'expiration_date',
        'received_at',
        'notes'
    ];

    protected $casts = [
        'received_at' => 'date',
        'expiration_date' => 'date'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
