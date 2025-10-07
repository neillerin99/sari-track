<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'credit_id',
        'item_id',
        'quantity',
        'price'
    ];
}
