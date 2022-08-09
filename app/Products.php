<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Products extends Model
{
    

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'products';

    protected $fillable = [
        'name',
        'unit_id',
        'description',
        'image',
        'price',
        
    ];

    public function unit(){
        return $this->belongsTo("App\Units");
    }

  

}
