<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $table = 'leads';

    protected $fillable = [
        'firstname',
        'lastname',
        'image',
        'title',
        'email',
        'phone'
        
    ];
}
