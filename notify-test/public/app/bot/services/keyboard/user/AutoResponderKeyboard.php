<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 2018-11-26
 * Time: 12:03
 */

namespace Pcs\Bot\services\keyboard\user;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AutoResponderKeyboard
{
    public static function get($chatId)
    {
        $keyboard = [
            [
                ['text' => CommandHelper::AUTO_RESPONDER_OFF],
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