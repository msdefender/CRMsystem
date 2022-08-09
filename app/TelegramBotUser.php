<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramBotUser extends Model
{
    protected $table = "tg_bot_users";

    protected $primaryKey = "id";

}
