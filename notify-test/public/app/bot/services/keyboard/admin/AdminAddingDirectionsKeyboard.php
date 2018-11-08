<?php

namespace Pcs\Bot\services\keyboard\admin;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AdminAddingDirectionsKeyboard
{
    public static function get()
    {
        return new ReplyKeyboardMarkup(
            [
                [
                    ["text" => CommandHelper::BACK]
                ],
                [
                    ["text" => CommandHelper::UNSUBSCRIBE]
                ]
            ],
            true,
            true
        );
    }
}