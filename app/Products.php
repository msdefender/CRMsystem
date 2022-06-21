    <?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
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
