<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'lastname',
        'image',
        'title',
        'email',
        'phone'
        
    ];
}
