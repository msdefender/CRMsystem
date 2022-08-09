<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    protected $table = 'pay_table';

    protected $fillable = [
        
        'id',
        'order_id',
        'pay_date',
        'is_pay',
        'sum',
        'debt'
        
        
       
    ];

    
    // public function customer(){
    //     return $this->belongsTo("App\Customers");
    // }


}
