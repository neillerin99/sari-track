<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'lastname',
        'number',
        'notes'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
