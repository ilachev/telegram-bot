<?php

namespace Pcs\Bot\services\keyboard;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class NotAdminKeyboard
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