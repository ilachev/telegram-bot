<?php

namespace Pcs\Bot\services\keyboard;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class YesNoKeyboard
{
    public static function get()
    {
        $keyboard = [
            [
                ["text" => CommandHelper::YES],
                ["text" => CommandHelper::NO],
            ]
        ];

        return new ReplyKeyboardMarkup(
            $keyboard,
            true,
            true
        );
    }
}