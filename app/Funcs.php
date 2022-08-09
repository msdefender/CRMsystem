<?php

namespace App;


class Funcs
{
    private $fee = "10000";
    private const BOT_API = "1689693630:AAEf1Z8TFnCxZpxY3tqxxUcOqEtY2T26pNI";
    public function make_user_id($id){

        $new_id = "U";
        for($i=0; $i<(4-strlen($id)); $i++){
            $new_id .= "0";
        }

        $new_id .= $id;

        return $new_id;
    }

    public function decode_user_id($id){

        $real_id = intval(str_replace("U", "", $id));

        return $real_id;
    }

    public function getServiceFee()
    {
        return $this->fee;
    }

    public function getAPI()
    {
        return self::BOT_API;
    }
}
