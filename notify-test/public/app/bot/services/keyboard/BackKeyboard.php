<?php

namespace Pcs\Bot\services\keyboard;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class BackKeyboard
{
    public static function get()
    {
        $keyboard = [
            [
                ["text" => CommandHelper::BACK]
            ],
            [
                ["text" => CommandHelper::UNSUBSCRIBE]
            ]
        ];

        return new ReplyKeyboardMarkup(
            $keyboard,
            true,
            true
        );
    }
}