<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'id',
        'name',
        'lastname',
        'image',
        'title',
        'email',
        'phone'
        
    ];

    public function data(){
        return $this->hasMany("App\Orders");
    }

    public function datas(){
        return $this->hasMany("App\Credit");
    }
}
