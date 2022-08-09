<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreements extends Model
{
    protected $table = 'agreements';

    protected $fillable = [
         'id',
        'title',
        'name',
        'description',
        'file_url'
        
    ];

    public function data(){
        return $this->hasMany("App\AgreementsF");
    }
}