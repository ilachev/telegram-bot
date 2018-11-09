<?php

namespace Pcs\Bot\services\keyboard\user;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class ManageRedirectsKeyboard
{
    public static function get($chatID)
    {
        $keyboard = [
            [
                ['text' => CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS],
                ['text' => CommandHelper::ADDING_REDIRECT],
            ],
            [
                ['text' => CommandHelper::BACK]
            ]
        ];

        return new ReplyKeyboardMarkup(
            $keyboard,
            true,
            true
        );
    }
}