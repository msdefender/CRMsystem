<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = [
       
        'name',
        'customer_id',
        'status',
        'payments',
        'has_agreements',
        'title',
        'email',
        'phone',
        'display',
        'total',
        'file_name'

        
    ];

    public function customer(){
        return $this->belongsTo("App\Customers");
    }
    
    public function data(){
        return $this->hasMany("App\Credit");
    }
}

