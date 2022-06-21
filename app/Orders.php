<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'status',
        'payments',
        '',
        'title',
        'email',
        'phone'
        
    ];
}

