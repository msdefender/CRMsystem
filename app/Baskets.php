<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Baskets extends Model
{
    protected $table = 'baskets';

    protected $fillable = [
        'name',
        'order_id',
        'product_id',
        'quantity',
        
    ];
}
