<?php

namespace Pcs\Bot\services\keyboard\admin;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AdminManageRedirectsKeyboard
{
    public static function get($chatID)
    {
        $keyboard = [
            [
                ['text' => CommandHelper::ADDING_DIRECTIONS],
                ['text' => CommandHelper::DELETING_DIRECTIONS],
            ],
            [
                ['text' => CommandHelper::BACK]
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