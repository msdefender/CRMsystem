<?php


namespace App\BotManager;


class BotManagerMessageList
{

    private $messages;

    public function __construct()
    {
        $this->messageInitilizer();
        $this->messageEnglishInitilizer();
        $this->messageRussianInitilizer();
    }

    public function getMessages($lang = "uz"){
        return $this->messages[$lang];
    }

    private function messageInitilizer(){

        $this->messages["default"] = [
                "choose_lang" => "🇺🇿 Tilni tanlang\n\n🇷🇺 Выберите язык\n\n🇺🇸 Choose the language",
            ];

        $this->messages["uz"] = [
            "user_not_found_error" => "Foydalanuvchi topilishida xatolik",
            "follow_required" => "⚠️ Botdan to'liq foydalanish uchun quyidagi kanallarga a'zo bo'lishingiz kerak 👇👇👇",
            "not_follow_warn" => "Barcha kanallarga obuna bo'lmagansiz. \nBotni to'liq ishlatish uchun, avval, obuna bo'ling. 👇👇👇",
            "you_can_use_the_bot" => "Ajoyib👍🏻 Endi botdan foydalana olasiz.",
            "i_joined" => "✅ A'zo bo'ldim",
            "lang_set" => "🇺🇿 O'zbek tili o'rnatildi ✅",
        ];
    }

    private function messageEnglishInitilizer(){

        $this->messages["en"] = [
            "user_not_found_error" => "Error on user finding",
            "follow_required" => "⚠️ In order to use the bot, you need to join the following channels 👇👇👇",
            "not_follow_warn" => "You have not joined all the channels yet. \nIn order to use the bot, you must join these channels 👇👇👇",
            "you_can_use_the_bot" => "Perfect👍🏻 Now, you can use the bot.",
            "i_joined" => "✅ I have joined",
            "lang_set" => "🇺🇸 English language is set ✅",
        ];
    }

    private function messageRussianInitilizer(){

        $this->messages["ru"] = [
            "user_not_found_error" => "Ошибка при нахождении пользователя",
            "follow_required" => "⚠️ Чтобы использовать бот, вам необходимо присоединиться к этим каналам 👇👇👇",
            "not_follow_warn" => "Вы еще не присоединились ко всем каналам. \nЧтобы использовать бот, вы должны присоединиться к этим каналам 👇👇👇",
            "you_can_use_the_bot" => "Идеально👍🏻 Теперь вы можете использовать бота.",
            "i_joined" => "✅ Я присоединился",
            "lang_set" => "🇷🇺 Русский язык установлен ✅",
        ];
    }


}