<?php

namespace Pcs\Bot\services\keyboard\admin;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AdminUserManagementKeyboard
{
    public static function get($chatID)
    {
        $keyboard = new ReplyKeyboardMarkup(
            [
                [
                    ['text' => CommandHelper::VIEW_MAPPING],
                    ['text' => CommandHelper::ADDING_MAPPING],
                ],
                [
                    ['text' => CommandHelper::EDITING_MAPPING],
                    ['text' => CommandHelper::DELETING_MAPPING],
                ],
                [
                    ['text' => CommandHelper::BACK],
                ]
            ],
            true,
            true
        );
        return $keyboard;
    }
}