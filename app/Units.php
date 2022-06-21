<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'id',
        'name',
       
    ];

    public function product(){
        return $this->hasMany("App\Products");
    }
}
