<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'baranggay',
        'city',
        'province',
        'status',
        'profile',
        'user_id'
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function bottles(): HasMany
    {
        return $this->hasMany(Bottle::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function restocks(): HasMany
    {
        return $this->hasMany(Restock::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
