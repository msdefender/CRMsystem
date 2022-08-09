<?php

namespace App\Http\Controllers\TelegramBot;

use App\Appeal;
use App\BotManager\TelegramBotManager;

use App\Http\Controllers\Controller;

use App\Media;
use App\Option;
use App\TelegramBotUser;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\ImageManagerStatic as Image;


class DoktorShahloBotController extends Controller
{
    private const API = "5266615065:AAFitzPkI0u8bLiYMpR9r52uTI5UZbenflY";
    const USERNAME = "ms_logistik_bot";

    private $service_fee = 0;

    private $client, $bot_manager;

    public function __construct(Request $request)
    {
        $this->client = new Client();
        $update = $request->all();

        $option = Option::where("option_key", "service_fee")->first();

        if($option !== null){
            $this->service_fee = $option->option_value;
        }
        else
        {
            $this->service_fee = 0;
        }

    /*    $data["chat_id"] = 1054808204;
        $data["text"] = json_encode($update, JSON_PRETTY_PRINT);

        $this->client->get("https://api.telegram.org/bot" . self::API . "/sendMessage", [RequestOptions::JSON => $data]);*/
        try {
            $this->bot_manager = new TelegramBotManager(self::API, $update);

         //   $this->bot_manager->sendMessage(["text" => json_encode($update, 128)]);
        }catch (\Exception $exception)
        {
            $data["chat_id"] = $update["message"]["chat"]["id"];
            $data["text"] = "construct error: " . $exception->getMessage() . "\n" . $exception->getLine();


        /*    $this->client->get("https://api.telegram.org/bot" . self::API . "/sendMessage", [RequestOptions::JSON => $data]);

            for ($i = 0; $i <= strlen($exception->getTraceAsString()); $i+=1000) {
                $data["text"] = substr($exception->getTraceAsString(), $i, $i+1000);
                $this->client->get("https://api.telegram.org/bot" . self::API . "/sendMessage", [RequestOptions::JSON => $data]);
            }*/

            exit();
        }

/*
        try{

        $update = $request->all();
        $data["chat_id"] = $update["message"]["chat"]["id"];
        $this->chat_id = $update["message"]["chat"]["id"];
        $data["text"] = "ch_id" . $this->chat_id;


        $this->client->get("https://api.telegram.org/bot" . self::API . "/sendMessage", [RequestOptions::JSON => $data]);
        }catch (\Exception $exception){
            exit();
        }*/
    }

    public function main()
    {

        if (!$this->bot_manager->isUserJoined())
            return;

        try {

            $this->bot_user = $this->bot_manager->getBotUser();

            $text = $this->bot_manager->getText();



            $data = $this->bot_manager->getData();
            $action = $this->bot_manager->getAction();

            if($text === "/start" && $action !== "start.bot"){
                $this->bot_manager->setAction(null);
                $action = null;
            }

        } catch (\Exception $exception) {
            $this->bot_manager->sendMessage(["text" => $exception->getMessage() . "this"]);
        }


            if ($data !== null) {
                $this->callBackAction($data);
                return;
            }
            else if ($action !== null) {
                $this->mainAction($action, $text);
                return;
            }
            else if ($text !== null) {
                    $this->textAction($text);
                    return;
                }

        $this->bot_manager->sendMessage(["text" => "null data"]);

    }

    private function textAction($text)
    {
        switch ($text) {
            case "/start":
            {
                $this->home("send");
                break;
            }
            case "/help":
            {
                $this->help();
                break;
            }
            case "/dasturchilar":
            {
                $this->dasturchilar();
                break;
            }

            default:{
                //$this->performAction($text);
            }
        }
    }


    private function callBackAction($data)
    {
        switch ($data) {
            case "profile":
            {
                try {
                    $this->visitProfile("edit");
                }catch (\Exception $exception){
                    $this->bot_manager->sendMessage(["text" => $exception->getMessage()]);
                }
                break;
            }

            case "go.home":
                {
                    try {
                        $this->home("edit");
                    }catch (\Exception $exception){
                        $this->bot_manager->sendMessage(["text" => $exception->getMessage()]);
                    }
                    break;
                }

            case "change.user.name":{
                $this->changeUserName();
                break;
            }

            case "change.phone.number":
            {
                $this->changePhoneNumber();
                break;
            }

            case "appeal":
            {
                $this->appeal();
                break;
            }

            case "replenish.account":
            {
                $this->replanishAccount();
                break;
            }

            case "start.appeal":
                {
                    $this->startAppeal();
                    break;
                }

            case "appeal.has.media":
            {
                $this->mediaUpload(true);
                break;
            }

            case "appeal.no.media":
            {
                $this->mediaUpload(false);
                break;
            }

            case "send.appeal":{
                $this->sendAppeal();
                break;
            }

            case "remove.appeal":{
                $this->removeAppeal();
                break;
            }

            case "appeal.history":{
                $this->appealHistory();
                break;
            }

            case "clear.drafts":{
                $this->clearDrafts();
                break;
            }

            case "send.media.and.appeal":{
                $this->sendMediaOfAppeal("edit");
                break;
            }


            default:{

                if(strpos($data, "show.appeal") !== false){
                    $appeal_id = str_replace("show.appeal.", "", $data);
                    $this->showAppeal($appeal_id);
                    break;
                }
            }



        }// callback
    }

    private function mainAction($action, $text = "")
    {
        switch ($action) {
            case "start.bot":
            {
                $this->askFullName();
                break;
            }

            case "get.full_name":
                {
                    $this->saveFullName($text);
                    $this->askPhoneNumber();
                    break;
                }

            case "get.phone_number":
                {
                    $result = $this->savePhoneNumber();
                    if($result)
                        $this->home("send");
                    break;
                }

            case "change.user.name":
                {
                    $this->updateUserName($text);
                    break;
                }

            case "change.phone.number":
            {
                $this->updatePhoneNumber($text);
            }

            case "get.appeal.text":
                {
                    $this->createAppeal($text);
                }

            default:{

                if(strpos($action, "appeal.has.media.") !== false){
                    $appeal_id = str_replace("appeal.has.media.", "", $action);
                    $this->addMedia($appeal_id);
                    break;
                }
            }




        }
    }



    // CallBack Data


    private function askFullName()
    {
        $this->bot_manager->setAction("get.full_name");
        $this->bot_manager->sendMessage([
            "text" => "👤 Ism-familiyangizni kiriting:"
        ]);
    }

    private function askPhoneNumber()
    {
        $this->bot_manager->setAction("get.phone_number");

        $keyboard = [
            "keyboard" => [
                    [
                      [
                          "text" => "📞 Telefon raqamni yuborish",
                          "request_contact" => true
                      ]
                    ]
                ],
            "resize_keyboard" => true,
            "one_time_keyboard" => true
        ];

        $this->bot_manager->sendMessage([
            "text" => "Telefon raqamingizni pastki knopka orqali yuboring ⤵️",
            "reply_markup" => json_encode($keyboard)
        ]);
    }

    private function saveFullName($text)
    {
        $user = TelegramBotUser::where("chat_id", $this->bot_manager->getChatId())->first();
        $user->full_name = $text;
        $user->update();
    }

    private function savePhoneNumber(): bool
    {
        $updates = $this->bot_manager->getUpdates();

        if(array_key_exists("contact", $updates["message"])) {

            $this->bot_manager->setAction(null);
            $user = TelegramBotUser::where("chat_id", $this->bot_manager->getChatId())->first();
            $user->phone_number = $updates["message"]["contact"]["phone_number"];
            $user->update();
            return true;
        }
        else{

            $this->askPhoneNumber();
            return false;
        }

    }

    private function home($type)
    {
        $reply_markup = [
          "inline_keyboard" => [
              [
                  [
                      "text" => "📤 Murojaat yo'llash",
                      "callback_data" => "appeal"
                  ],
                  [
                      "text" => "🗄 Murojaatlar tarixi",
                      "callback_data" => "appeal.history"
                  ],
              ],
              [
                  [
                      "text" => "👤 Profil",
                      "callback_data" => "profile"
                  ],
                  [
                      "text" => "💳 Hisobni to'ldirish",
                      "callback_data" => "replenish.account"
                  ],
              ],
              [
                  [
                      "text" => "🤔 Botni qanday ishlataman?",
                      "url" => "https://youtu.be/4NM77FR69sU"
                  ],
              ],
          ],
            "remove_keyboard"=> true
        ];

        $text = "👩‍⚕️Shifokor <b>Shahlo Turdikulova</b> online konsultatsiya xizmatidan foydalanayapsiz.";
        switch ($type){
            case "send":{
                $this->bot_manager->sendMessage([
                    "text" => $text,
                    "parse_mode" => "HTML",
                    "reply_markup" => $reply_markup
                ]);
                break;
            }
            case "edit":{
                $this->bot_manager->sendBot("editMessageText", [
                    "text" => $text,
                    "message_id" => $this->bot_manager->getMessageId(),
                    "parse_mode" => "HTML",
                    "reply_markup" => $reply_markup
                ]);
                break;
            }

        }

    }

    private function visitProfile($type)
    {
        $this->bot_manager->setAction(null);
        try {
            $user = TelegramBotUser::where("chat_id", $this->bot_manager->getChatId())->first();

            $text = "🔑 <b>Foydalanuvchi ID`si:</b> " . $this->make_user_id($user->id)."\n\n";
            $text .= "👤 <b>Ism-familiya:</b> " . $user->full_name . "\n\n";
            $text .= "📞 <b>Telefon raqam:</b> " . $user->phone_number. "\n\n";
            $text .= "💳 <b>Hisobdagi mablag':</b> " . $user->balance. " so'm\n\n";
            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "✏️ Ism-familiyani o'zgartirish",
                            "callback_data" => "change.user.name"
                        ],
                    ],
                    [
                        [
                            "text" => "✏️ Telefon raqamni o'zgartirish",
                            "callback_data" => "change.phone.number"
                        ],
                    ],
                    [
                        [
                            "text" => "⬅️ Ortga",
                            "callback_data" => "go.home"
                        ],
                    ],
                ]
            ];

            switch ($type){
                case "edit":{

                    $this->bot_manager->sendBot("editMessageText", [
                        "text" => $text,
                        "message_id" => $this->bot_manager->getMessageId(),
                        "parse_mode" => "HTML",
                        "reply_markup" => $inline_keyboard
                    ]);
                    break;
                }

                case "send":{

                    $this->bot_manager->sendMessage([
                        "text" => $text,
                        "parse_mode" => "HTML",
                        "reply_markup" => $inline_keyboard
                    ]);
                    break;
                }
            }


        }catch (\Exception $exception){
            $this->bot_manager->sendMessage([
                "text" => "visit profile: " . $exception->getMessage(),
            ]);
        }
    }

    public function make_user_id($id){

        $new_id = "U";
        for($i=0; $i<(4-strlen($id)); $i++){
            $new_id .= "0";
        }

        $new_id .= $id;

        return $new_id;
    }

    private function changeUserName()
    {
        $this->bot_manager->setAction("change.user.name");
        $user = $this->bot_manager->getBotUser();

        $text = "Sizning ismingiz: " . $user->full_name . "\n\n";
        $text .= "Ismingizni o'zgartirish uchun qayta yozib yuboring.";

        $inline_keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "⬅️ Ortga",
                        "callback_data" => "profile"
                    ],
                ],
            ]
        ];

        $this->bot_manager->sendBot("editMessageText", [
            "text" => $text,
            "message_id" => $this->bot_manager->getMessageId(),
            "parse_mode" => "HTML",
            "reply_markup" => $inline_keyboard
        ]);
    }

    private function updateUserName($name)
    {
        try {
            $this->bot_manager->setAction(null);

            $user_id = $this->bot_manager->getBotUser()->id;

            $user = TelegramBotUser::where("id", $user_id)->first();

            if ($user === null) {
                $this->bot_manager->sendMessage([
                    "text" => "foydalanuvchi topilmadi!!!",
                ]);
            } else {

                $user->full_name = $name;
                $user->update();

                $this->bot_manager->sendBot("deleteMessage", [
                    "message_id" => $this->bot_manager->getMessageId() - 1,
                    "chat_id" => $this->bot_manager->getChatId()
                ]);

                $this->visitProfile("send");

            }
        }catch(\Exception $exception){
            $this->bot_manager->sendMessage([
                "text" => "update_user_name: " . $exception->getMessage(),
            ]);
        }


    }

    private function changePhoneNumber(){
        $this->bot_manager->setAction("change.phone.number");
        $user = $this->bot_manager->getBotUser();

        $text = "Sizning telefon raqamingiz: " . $user->phone_number . "\n\n";
        $text .= "Raqamingizni o'zgartirish uchun qayta yozib yuboring.";

        $inline_keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "⬅️ Ortga",
                        "callback_data" => "profile"
                    ],
                ],
            ]
        ];

        $this->bot_manager->sendBot("editMessageText", [
            "text" => $text,
            "message_id" => $this->bot_manager->getMessageId(),
            "parse_mode" => "HTML",
            "reply_markup" => $inline_keyboard
        ]);
    }

    private function updatePhoneNumber($phone_number)
    {
        try {
            $this->bot_manager->setAction(null);

            $user_id = $this->bot_manager->getBotUser()->id;

            $user = TelegramBotUser::where("id", $user_id)->first();

            if ($user === null) {
                $this->bot_manager->sendMessage([
                    "text" => "foydalanuvchi topilmadi!!!",
                ]);
            } else {

                $user->phone_number = $phone_number;
                $user->update();

                $this->bot_manager->sendBot("deleteMessage", [
                    "message_id" => $this->bot_manager->getMessageId() - 1,
                    "chat_id" => $this->bot_manager->getChatId()
                ]);

                $this->visitProfile("send");

            }
        }catch(\Exception $exception){
            $this->bot_manager->sendMessage([
                "text" => "update_phone_number: " . $exception->getMessage(),
            ]);
        }
    }

    private function appeal()
    {
        try {
            $user = $this->bot_manager->getBotUser();

            $fee = $this->service_fee;

            if ($user->balance < $fee) {
                $text = "Murojaat yuborishingiz uchun hisobingizda eng kamida " . $fee . " so'm bo'lishi kerak.\n\nHisobingizdagi mablag' yetarli emas.";

                $inline_keyboard = [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "💳 Hisobni to'ldirish",
                                "callback_data" => "replenish.account"
                            ],
                        ],
                        [
                            [
                                "text" => "⬅️ Ortga",
                                "callback_data" => "go.home"
                            ],
                        ],
                    ]
                ];

                $this->bot_manager->sendBot("editMessageText", [
                    "text" => $text,
                    "message_id" => $this->bot_manager->getMessageId(),
                    "parse_mode" => "HTML",
                    "reply_markup" => $inline_keyboard
                ]);

            }
            else {

                $text = "Murojaat yuborishda 1 ta xabar va maksimum 6 tagacha bo'lgan rasm yuborish mumkin. \nMurojaat qilishni boshlashda ma'lumotlaringizni shunga moslashtiring.";

                $inline_keyboard = [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "➕ Murojaatni yaratish",
                                "callback_data" => "start.appeal"
                            ],
                        ],
                        [
                            [
                                "text" => "⬅️ Ortga",
                                "callback_data" => "go.home"
                            ],
                        ],
                    ]
                ];

                $this->bot_manager->sendBot("editMessageText", [
                    "text" => $text,
                    "message_id" => $this->bot_manager->getMessageId(),
                    "parse_mode" => "HTML",
                    "reply_markup" => $inline_keyboard
                ]);
            }
        }catch (\Exception $exception){
            $this->bot_manager->sendMessage([
                "text" => "appeal: " . $exception->getMessage(),
            ]);
        }

    }

    private function replanishAccount()
    {
        $id = $this->make_user_id($this->bot_manager->getBotUser()->id);
        $text = "Hisobni to'ldirish PayMe ilovasi orqali amalga oshiriladi:\n\n";
        $text .= "1. PayMega kiring va <b>Shahlo Med Servis</b> deb izlang\n\n";
        $text .= "2. Foydalanuvchi ID`siga ID raqamingizni kiriting. Sizning ID: <b>".$id."</b>\n\n";
        $text .= "3. To'lov summasini kiriting va qarabsizki, mablag' hisobingizga kelib tushadi\n\n";
        $text .= "<i>!!! To'lov qilingandan so'ng, mablag'ni qaytarish imkonsiz. O'zingizga kerakli miqdorda to'lov qilishni tavsiya etamiz.</i>\n\n";

        $inline_keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "⬅️ Ortga",
                        "callback_data" => "go.home"
                    ],
                ],
            ]
        ];

        $this->bot_manager->sendBot("editMessageText", [
            "text" => $text,
            "message_id" => $this->bot_manager->getMessageId(),
            "parse_mode" => "HTML",
            "reply_markup" => $inline_keyboard
        ]);
    }

    private function startAppeal()
    {
        $this->bot_manager->setAction("get.appeal.text");

        $text = "Murojaat matnini yozib yuboring:";

        $this->bot_manager->sendBot("editMessageText", [
            "text" => $text,
            "message_id" => $this->bot_manager->getMessageId(),
        ]);

    }

    private function createAppeal(string $text)
    {
        try {
            $user = $this->bot_manager->getBotUser();

            $appeal = new Appeal();
            $appeal->content = $text;
            $appeal->user_id = $user->id;
            $appeal->save();

            $this->bot_manager->setAction("appeal.id." . $appeal->id);

            $bot_text = "Murojaatingizda rasm yuborishingiz kerakmi?";

            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "Ha",
                            "callback_data" => "appeal.has.media"
                        ],
                        [
                            "text" => "Yo'q",
                            "callback_data" => "appeal.no.media"
                        ],
                    ],
                ]
            ];

            $this->bot_manager->sendMessage([
                "text" => $bot_text,
                "parse_mode" => "HTML",
                "reply_markup" => $inline_keyboard
            ]);
        }catch (\Exception $exception){
            $this->bot_manager->setAction(null);
            $this->bot_manager->sendMessage([
                "text" => "appeal create: " . $exception->getMessage(),
            ]);
        }

    }

    private function mediaUpload(bool $media_status)
    {
        $appeal_id = explode(".", $this->bot_manager->getAction())[2];

        $appeal = Appeal::where("id", $appeal_id)->first();

        if(!$media_status){

            $text = "<b>Sizning murojaat: </b>\n\n<i>".$appeal->content."</i>";

            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "✅ Murojaatni yuborish",
                            "callback_data" => "send.appeal"
                        ],
                    ],
                    [
                        [
                            "text" => "❌  Murojaatni bekor qilish",
                            "callback_data" => "remove.appeal"
                        ],
                    ],
                ]
            ];


            $this->bot_manager->sendBot("editMessageText", [
                "text" => $text,
                "message_id" => $this->bot_manager->getMessageId(),
                "parse_mode" => "HTML",
                "reply_markup" => $inline_keyboard
            ]);

        }
        else{

            $appeal->has_media = 1;
            $appeal->update();

            $this->bot_manager->setAction("appeal.has.media.".$appeal_id);

            $this->bot_manager->sendBot("editMessageText", [
                "text" => "Murojaat uchun rasmlarni yuboring. (Maksimum 6 ta rasm)",
                "message_id" => $this->bot_manager->getMessageId(),
                "parse_mode" => "HTML",
            ]);
        }
    }

    private function sendAppeal()
    {
        try{
            $user = TelegramBotUser::where("chat_id", $this->bot_manager->getChatId())->first();

            if($user->balance - $this->service_fee < 0){
                $this->bot_manager->sendBot("editMessageText", [
                    "text" =>  "Hisobingizda mablag' yetarli emas. Sizni bosh bo'limga qaytaramiz.",
                    "message_id" => $this->bot_manager->getMessageId(),
                    "parse_mode" => "HTML",
                ]);
                sleep(3);
                $this->home("edit");
            }
            else {
                $appeal_id = explode(".", $this->bot_manager->getAction())[2];

                $appeal = Appeal::where("id", $appeal_id)->first();

                $appeal->status = "pending";
                $appeal->update();


                $user->balance = $user->balance - $this->service_fee;
                $user->update();

                $text = "✅ Murojaatingiz yuborildi. Tez orada javob beriladi";

                $inline_keyboard = [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "⬅️ Asosiy bo'limga qaytish",
                                "callback_data" => "go.home"
                            ],
                        ],
                    ]
                ];

                $this->bot_manager->sendBot("editMessageText", [
                    "text" => $text,
                    "message_id" => $this->bot_manager->getMessageId(),
                    "parse_mode" => "HTML",
                    "reply_markup" => $inline_keyboard
                ]);

            }//end else


        }catch (\Exception $exception){
            $this->bot_manager->setAction(null);
            $this->bot_manager->sendMessage([
                "text" => "appeal create: " . $exception->getMessage(),
            ]);
        }
    }

    private function removeAppeal()
    {
        $appeal_id = explode(".", $this->bot_manager->getAction())[2];

        $appeal = Appeal::where("id", $appeal_id)->first();

        if($appeal !== null) {
            $appeal->delete();

            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "📤  Murojaatni yuborish",
                            "callback_data" => "appeal"
                        ],
                    ],
                    [
                        [
                            "text" => "⬅️ Asosiy bo'limga qaytish",
                            "callback_data" => "go.home"
                        ],
                    ],
                ]
            ];


            $this->bot_manager->sendBot("editMessageText", [
                "text" => "Murojaat o'chirildi.",
                "message_id" => $this->bot_manager->getMessageId(),
                "parse_mode" => "HTML",
                "reply_markup" => $inline_keyboard
            ]);
        }
    }

    private function appealHistory()
    {
        $appeals = Appeal::where("user_id", $this->bot_manager->getBotUser()->id)->get();

        if(count($appeals) === 0){
            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "⬅️ Ortga",
                            "callback_data" => "go.home"
                        ],
                    ],
                ]
            ];

            $this->bot_manager->sendBot("editMessageText", [
                "text" => "Siz hali hech qanday murojaat qilmagansiz",
                "message_id" => $this->bot_manager->getMessageId(),
                "parse_mode" => "HTML",
                "reply_markup" => $inline_keyboard
            ]);
        }
        else {
            $text = ""; $counter = 0; $key = array(); $key_row = array();
            foreach ($appeals as $appeal){
                $counter++;
                $text.= "<b>Raqami: </b>" . $appeal->id . "\n";
                $text.= "<b>Sanasi: </b>" . $appeal->created_at . "\n";

                $status = "";
                if($appeal->status === "open")
                    $status = "📄 Qoralama";
                elseif($appeal->status === "pending")
                    $status = "🟡 Javob kutilmoqda";
                elseif($appeal->status === "closed")
                    $status = "🟢 Javob berilgan";
                $text.= "<b>Holati: </b>" . $status . "\n";
                $text.= "<b>Parcha: </b>" . substr($appeal->content, 0, 50) . "...\n\n\n";

                array_push($key, [
                    "text" => "#".$appeal->id,
                    "callback_data" => "show.appeal.".$appeal->id,
                ]);

                if($counter >= 5 && $counter % 5 == 0){
                    array_push($key_row, $key);
                    $key = array();
                }

            }
                array_push($key_row, $key);


            if(Appeal::where("user_id", $this->bot_manager->getBotUser()->id)->where("status", "open")->exists()) {
                array_push($key_row, [[
                    "text" => "🗑️ Qoralamalarni o'chirish",
                    "callback_data" => "clear.drafts"
                ]]);
            }

            array_push($key_row, [[
                "text" => "⬅️ Ortga",
                "callback_data" => "go.home"
            ]]);


            $inline_keyboard = [
                "inline_keyboard" => $key_row
            ];

            $this->bot_manager->sendBot("editMessageText", [
                "text" => $text,
                "message_id" => $this->bot_manager->getMessageId(),
                "parse_mode" => "HTML",
                "reply_markup" => $inline_keyboard
            ]);


        }
    }

    private function clearDrafts()
    {
        try {
            $appeals = Appeal::where("user_id", $this->bot_manager->getBotUser()->id)->where("status", "open")->get();

            if ($appeals !== null) {
                foreach ($appeals as $appeal) {

                    if($appeal->has_media === 1){
                        $media = Media::where("appeal_id", $appeal->id)->get();

                        if($media !== null){
                            foreach ($media as $data){
                                if(file_exists(public_path("uploads/").$data->file_name)){
                                    unlink(public_path("uploads/".$data->file_name));
                                }
                                $data->delete();
                            }
                        }
                    }

                    $appeal->delete();
                }
            }

            $this->appealHistory();
        }catch (\Exception $exception){
            $this->bot_manager->setAction(null);
            $this->bot_manager->sendMessage([
                "text" => "appeal create: " . $exception->getMessage(),
            ]);
        }
    }

    private function showAppeal($appeal_id)
    {
        try {
            $appeal = Appeal::where("id", $appeal_id)->first();
            $text = "";
            $text .= "<b>Raqami: </b>" . $appeal->id . "\n";
            $text .= "<b>Sanasi: </b>" . $appeal->created_at . "\n";
            $status = "";
            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "⬅️ Ortga",
                            "callback_data" => "appeal.history"
                        ],
                    ],
                ]
            ];

            if ($appeal->status === "open") {
                $status = "📄 Qoralama";
            } elseif ($appeal->status === "pending") {
                $status = "🟡 Javob kutilmoqda";
            } elseif ($appeal->status === "closed") {
                $status = "🟢 Javob berilgan";
            }
            $text .= "<b>Holati: </b>" . $status . "\n";
            $text .= "<b>Murojaat: </b>\n<i>" . $appeal->content . "</i>\n";

            $params = [
                "text" => $text,
                "message_id" => $this->bot_manager->getMessageId(),
                "parse_mode" => "HTML",
            ];
            if($appeal->status !== "closed")
            {
                $params["reply_markup"] = $inline_keyboard;
            }

                $this->bot_manager->sendBot("editMessageText", $params);
                
                $text  ="<b>Murojaat javobi:</b>\n\n";
            if ($appeal->status === "closed") {
                $answer = $appeal->answer;
                //
                $l = strlen($answer);
                // try{
            for($i = 0; $i < ceil($l / 4000); $i++){
            
                $n = strlen($answer);
                if($n >= 4000){
                    $str = substr($answer, 0 , 4000);
                    $answer = substr($answer, 4000 * ($i + 1), $n - strlen($str));
                }else{
                    $str = $answer;
                }
               
                if($i == 0){
                    $data =  $text . $str;
                }else{
                    $data = $str;
                }
                
                $this->bot_manager->sendMessage([
                    "text" => $data,
                    "parse_mode" => "HTML",
                    "reply_markup" => $inline_keyboard
                ]);
                
            }
            
            //
                
                
            }

        }catch (\Exception $exception){
            $this->bot_manager->setAction(null);
            $this->bot_manager->sendMessage([
                "text" => "appeal create: " . $exception->getMessage(),
            ]);
        }

    }

    private function addMedia($appeal_id)
    {
        $media = Media::where("appeal_id", $appeal_id)->get();

        if(count($media) <= 6) {
            try {

                $updates = $this->bot_manager->getUpdates();
                if (array_key_exists("photo", $updates["message"])) {
                    $id_f = count($updates["message"]["photo"]) - 1;
                    $file_id = $updates["message"]["photo"][$id_f]["file_id"];

                    $link = 'https://api.telegram.org/bot' . self::API . "/getFile?file_id=" . $file_id;

                    $client = new Client();
                    $result = $client->get($link);

                    $json_response = json_decode($result->getBody(), true);
                    if ($json_response['ok'] == 'true') {
                        //Telegram file link
                        $telegram_file_link = 'https://api.telegram.org/file/bot' . self::API . '/' . $json_response['result']['file_path'];

                        $upload_path = public_path("uploads/");

                        $file_name = time() . "_" . basename($telegram_file_link);

                        $ch = curl_init($telegram_file_link);
                        $fp = fopen($upload_path . $file_name, 'wb');
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);

                        $media = new Media();
                        $media->file_name = $file_name;
                        $media->appeal_id = $appeal_id;
                        $media->save();

                        $media_count = Media::where("appeal_id", $appeal_id)->count();

                        if($media_count < 6) {
                            $inline_keyboard = [
                                "inline_keyboard" => [
                                    [
                                        [
                                            "text" => "☑️️ Yuborish",
                                            "callback_data" => "send.media.and.appeal"
                                        ],
                                    ],
                                ]
                            ];

                            $this->bot_manager->sendMessage([
                                "text" => $media_count . " ta rasm kiritdingiz. Yana " . (6 - $media_count) . " ta rasm yuborishingiz mumkin yoki murojaatni yuboring.",
                                "reply_markup" => $inline_keyboard
                            ]);
                        }
                        elseif ($media_count === 6){
                            $this->sendMediaOfAppeal("send");
                        }
                    }
                } else {
                    $this->bot_manager->sendMessage([
                        "text" => "Faqat rasm yuborishingiz mumkin."
                    ]);
                }
            } catch (\Exception $exception) {
                $this->bot_manager->sendMessage([
                    "text" => "add media: " . $exception->getMessage() . substr($exception->getTraceAsString(), 0, 1000),
                ]);
            }
        } // end if count 6
    }

    private function sendMediaOfAppeal($type)
    {
        $action = $this->bot_manager->getAction();
        if(strpos($action, "appeal.has.media.") !== false) {
            $appeal_id = str_replace("appeal.has.media.", "", $action);


            $appeal = Appeal::where("id", $appeal_id)->first();

            $this->bot_manager->setAction("appeal.id." . $appeal->id);

            $text = "<b>Sizning murojaat: </b>\n\n<i>" . $appeal->content . "</i>";

            $inline_keyboard = [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "✅ Murojaatni yuborish",
                            "callback_data" => "send.appeal"
                        ],
                    ],
                    [
                        [
                            "text" => "❌  Murojaatni bekor qilish",
                            "callback_data" => "remove.appeal"
                        ],
                    ],
                ]
            ];

            switch ($type) {
                case "edit":
                {
                    $this->bot_manager->sendBot("editMessageText", [
                        "text" => $text,
                        "message_id" => $this->bot_manager->getMessageId(),
                        "parse_mode" => "HTML",
                        "reply_markup" => $inline_keyboard
                    ]);
                    break;
                }
                case "send":
                {
                    $this->bot_manager->sendMessage([
                        "text" => $text,
                        "parse_mode" => "HTML",
                        "reply_markup" => $inline_keyboard
                    ]);
                    break;
                }
            }// end switch
        }
    }

    private function dasturchilar()
    {
        $inline_keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "Programmer UZ",
                        "url" => "https://www.programmer.uz"
                    ],
                ],
                [
                    [
                        "text" => "Telegramga ulanish",
                        "url" => "https://t.me/programmer_uz"
                    ],
                ],
            ]
        ];

        $text = "Bot <b>Programmer UZ</b> tomonidan ishlab chiqilgan.\n\nDasturchi: Bobobek Turdiyev\n\nSizga ham shu kabi loyihalar kerak bo'lsa quyidagi manzillar orqali biz bilan bog'lanishingiz mumkin.";

        $this->bot_manager->sendMessage([
            "text" => $text,
            "parse_mode" => "HTML",
            "reply_markup" => $inline_keyboard
        ]);
    }

    private function help()
    {
        $text = "Botdan foydalanish uchun quyidagi video bilan tanishib chiqing ⤵️ \n\nhttps://youtu.be/4NM77FR69sU";

        $this->bot_manager->sendMessage([
            "text" => $text,
            "parse_mode" => "HTML",
        ]);
    }


}
