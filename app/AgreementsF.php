<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgreementsF extends Model
{
    protected $table = 'agreement_fields';

    protected $fillable = [
     
        'agreement_id',
        'field_name',
        'field_value',
        'input_name',
       
        
    ];
  

    public function agreement(){
        return $this->belongsTo("App\Agreements");
    }
}