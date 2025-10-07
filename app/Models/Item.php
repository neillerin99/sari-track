<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'quantity',
        'expiration_date',
        'cost_price',
        'selling_price',
        'is_active'
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
}
