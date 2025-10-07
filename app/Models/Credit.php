<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'quantity',
        'total_price',
        'name',
        'status',
        'paid_on',
        'notes'
    ];
    protected $casts = [
        'paid_on' => 'date'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
