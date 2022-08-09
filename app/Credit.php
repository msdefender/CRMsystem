<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credits';

    protected $fillable = [
        
        'id',
        'order_id',
        'month',
        'debt',
        'first_pay',
        'balance',
        'percentage',
        'start_date'
        
       
    ];

    
    public function customer(){
        return $this->belongsTo("App\Customers");
    }
    // public function customer(){
    //     return $this->belongsTo("App\Customers");
    // }


}
