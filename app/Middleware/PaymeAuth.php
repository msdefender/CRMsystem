<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PaymeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $header = $request->header('Authorization');

        if($header == null || $header == ""){
            $result["error"]["code"] = -32504;
            $result["error"]["message"]["uz"] = "Ausentifikatsiyani tekshirib bo‘lmadi";
            $result["error"]["message"]["en"] = "Auth is failed";
            $result["error"]["message"]["ru"] = "Аутентификация не удалась";
            return response()->json($result, 200);
        }

        $header_request = explode(" ", $header);
//UGF5Y29tOlYmT2FIek5pRzBPOD9kSmlqOWUyb3N5cndWR1dRYTNVSWNVIw==
//773R6IzG3ZIhadf?unAP65kIg45z&5%EH@GU -- test
//kNK85W1?oAuVX7J6GYHSGf680agArZdsNGzU

        $login_full = base64_decode($header_request[1]);
        $login_arr = explode(":", $login_full);


        if($login_arr[0] == "Paycom" && $login_arr[1] == "knSFTj1ygCmZgiDSFWvRH@cuOHQ@OZi1AqFU")
        {
            return $next($request);
        }
        else {

            $result["error"]["code"] = -32504;
            $result["error"]["message"]["uz"] = "Ausentifikatsiyani tekshirib bo‘lmadi";
            $result["error"]["message"]["en"] = "Auth is failed";
            $result["error"]["message"]["ru"] = "Аутентификация не удалась";
            return response()->json($result, 200);
        }
    }
}
