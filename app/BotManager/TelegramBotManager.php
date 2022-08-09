<?php


namespace App\BotManager;

use App\TelegramBotUser;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class TelegramBotManager
{
    private $api, $update, $message, $text, $chat_id, $message_id, $data, $data_chat_id, $data_message_id, $url, $username, $first_name, $last_name, $bot_user_id;

    private  $bot_user;

    private $user_joined = false;

    private const URL_ENDPOINT = "https://api.telegram.org/bot";

    function __construct($api, $update){

       // $this->bot = json_encode($bot);
        $this->api = $api;
        $this->update = $update;


        try {
            if (array_key_exists("message", $update)) {


                $this->message = $update["message"];

                if (array_key_exists("text", $this->message)) {
                    $this->text = $this->message["text"]; // text
                }
                else{
                    $this->text = ""; // text
                }


                if (array_key_exists("username", $this->message["from"])) {
                    $this->username = $this->message["from"]["username"];
                }

                $this->chat_id = $this->message["chat"]["id"]; //chat id
                $this->message_id = $this->message["message_id"];
                $this->first_name = $this->message["from"]["first_name"];
                $this->bot_user_id = $this->message["from"]["id"];

                if (array_key_exists("username", $this->message["from"])) {
                    $this->username = $this->message["from"]["username"];
                }

            }

            if (array_key_exists("callback_query", $update)) {
                $this->data = $update["callback_query"]["data"]; // callback_data
                $query_id = $update["callback_query"]["id"]; // id
                $this->chat_id = $update["callback_query"]["message"]["chat"]["id"];
                $this->message_id = $update["callback_query"]["message"]["message_id"];
                $this->first_name = $update["callback_query"]["from"]["first_name"];
                $this->bot_user_id = $update["callback_query"]["from"]["id"];

                if (array_key_exists("username", $update["callback_query"]["from"])) {
                    $this->username = $update["callback_query"]["from"]["username"];
                }

                $this->sendBot("answerCallbackQuery",[
                   "callback_query_id"=> $query_id
                ]);
            }

            $this->url = self::URL_ENDPOINT . $this->api . "/";

            $this->storeUserInformation();

            $this->checkUserFollowOnChannel();

        }catch (\Exception $exception){
            $client = new Client();

            $data["chat_id"] = $update["message"]["chat"]["id"];
            $data["text"] = "bot manager construct error: " . $exception->getMessage() . "\n" . $exception->getLine();


            $client->get("https://api.telegram.org/bot" . $api . "/sendMessage", [RequestOptions::JSON => $data]);

         //   $this->sendMessage(["text" => $exception->getMessage() . " \nline" . $exception->getLine()]);
        }


    }

    private function setUrl($method){
        return $this->url = self::URL_ENDPOINT . $this->api . "/". $method;
    }

    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chat_id;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @return mixed
     */
    public function getDataChatId()
    {
        return $this->data_chat_id;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getDataMessageId()
    {
        return $this->data_message_id;
    }

    public function sendBot($method, $data = []){
        if(!array_key_exists("chat_id", $data)){
            $data["chat_id"] = $this->chat_id;
        }

        $url = self::URL_ENDPOINT . $this->api . "/". $method;

        $client = new Client();

        $response = $client->get( $url, [RequestOptions::JSON => $data]);

        return $response->getBody()->getContents();

    }

    public function sendBotGetResponse($method, $data = []){
        if(!array_key_exists("chat_id", $data)){
            $data["chat_id"] = $this->chat_id;
        }

        $url = self::URL_ENDPOINT . $this->api . "/". $method;

        $client = new Client();

        $response = $client->get( $url, [RequestOptions::JSON => $data]);

        return $response;

    }

    public function sendMessage($data = []){

        $url = $this->setUrl("sendMessage");

        return $this->runMethod($url, $data);

    }

    public function sendPhoto($data = []){

        $url = $this->setUrl("sendPhoto");

        return $this->runMethod($url, $data);

    }

    private function runMethod($url, $data){

        if(!array_key_exists("chat_id", $data)){
            $data["chat_id"] = $this->chat_id;
        }

        $client = new Client();

        $response = $client->get( $url, [RequestOptions::JSON => $data]);

        $data["status_code"] = $response->getStatusCode();
        $data["response_content"] = $response->getBody();
//403 error
        return $data;
    }

    public function getText(){
        return $this->text;
    }

    private function storeUserInformation(){

        $user = TelegramBotUser::where("chat_id", $this->chat_id)->first();

        if($user === null){
            try {
                $bot_user = new TelegramBotUser();
                $bot_user->chat_id = $this->chat_id;
                $bot_user->full_name = $this->first_name;
                $bot_user->action = "start.bot";

                if (!empty($this->username) && $this->username !== null)
                    $bot_user->username = $this->username;

                $bot_user->save();

                $this->bot_user = $bot_user;
            }catch (\Exception $e){
                $this->sendMessage(["text" => "Error: " . $e->getMessage()]);
            }

        }
        else{
            if(!empty($this->username) && $user->username !== $this->username)
                $user->username = $this->username;

            $user->update();

            $this->bot_user = $user;

        }

    }

    private function checkUserFollowOnChannel(){

       /* $channels = [["channel_username" => "shahlomedservis", "name" => "Shahlo Med Servis"]];
*/
$channels = [];
        if(count($channels) > 0) {

            $keyboard_array = array();

            foreach ($channels as $channel) {
                try {
                    $user_chat_data = $this->sendBot("getChatMember", [
                        "chat_id" => "@" . $channel["channel_username"],
                        "user_id" => $this->bot_user_id
                    ]);
                } catch (\Exception $e) {
                    if ($e->getCode() == 400) {
                        $this->sendMessage(["text" => "user not found error"]);
                    } else
                        $this->sendMessage(["text" => $e->getMessage()]);
                }

                array_push($keyboard_array, [[
                    'text' => $channel["name"],
                    'url' => "https://t.me/" . $channel["channel_username"]
                ]]);

                $user_array_data = json_decode($user_chat_data);

                $status = $user_array_data->result->status;

                if ($status !== "member" && $status !== "administrator" && $status !== "creator") {
                    $this->user_joined = false;
                    break;
                } else {
                    $this->user_joined = true;
                }

            }// end foreach

            if ($this->data === "confirm.join" && !$this->user_joined) {

                $inline_keyboard = [
                    "inline_keyboard" => $keyboard_array
                ];
                //check join
                try {
                    $this->sendBot("editMessageText", ["chat_id" => $this->chat_id, "message_id" => $this->message_id,
                        "text" => "Barcha kanallarga obuna bo'lmagansiz. \nBotni to'liq ishlatish uchun, avval, obuna bo'ling. 👇👇👇\n\nBotni qayta ishga tushurish uchun /start ni bosing",
                        "reply_markup" => $inline_keyboard
                    ]);
                } catch (\Exception $e) {
                    $this->sendMessage(["text" => $e->getMessage() . " \nline" . $e->getLine()]);
                }
            } elseif ($this->data === "confirm.join" && $this->user_joined) {
                $this->sendBot("deleteMessage", ["chat_id" => $this->chat_id, "message_id" => $this->message_id
                ]);

                $this->sendMessage(["text" => "Ajoyib👍🏻 Endi botdan foydalana olasiz."]);
            } else
                if (!$this->user_joined) {

                    array_push($keyboard_array, [[
                        'text' => "✅ A'zo bo'ldim",
                        'callback_data' => "confirm.join"
                    ]]);

                    $inline_keyboard = [
                        "inline_keyboard" => $keyboard_array
                    ];

                    $this->sendMessage([
                        "text" => "Quyidagi kanallarga a'zo bo'lishingiz shart",
                        "reply_markup" => $inline_keyboard
                    ]);
                }
        }else{
            $this->user_joined = true;
        }
    }

    /**
     * @return bool
     */
    public function isUserJoined(): bool
    {
        return $this->user_joined;
    }

    public function setAction($action){

        $bot_user = TelegramBotUser::where("chat_id", $this->chat_id)->first();
        $bot_user->action = $action;
        $bot_user->update();


    }

    public function getAction(){
        $bot_user = TelegramBotUser::where("chat_id", $this->chat_id)->first();
        return $bot_user->action;
    }

    public function getBotUser(){
        return $this->bot_user;
    }

    public function getUpdates()
    {
        return $this->update;
    }


}
