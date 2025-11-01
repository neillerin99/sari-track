<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BottleItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'bottle_id',
        'item_id',
        'quantity'
    ];
}
