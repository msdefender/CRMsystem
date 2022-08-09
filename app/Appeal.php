<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    protected $table = "appeals";

    public function user(){
        return $this->belongsTo("App\TelegramBotUser", "user_id");
    }
}
