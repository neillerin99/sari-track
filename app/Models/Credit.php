<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'store_id',
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

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
