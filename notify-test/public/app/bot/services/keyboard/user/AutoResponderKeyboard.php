<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 2018-11-26
 * Time: 12:03
 */

namespace Pcs\Bot\services\keyboard\user;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\AutoResponderStatusRepository;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AutoResponderKeyboard
{
    public static function get($chatId)
    {
        $statusRepository = new AutoResponderStatusRepository();

        $status = $statusRepository->getStatusForCurrentUser($chatId);

        if ($status == 0) {
            $keyboard = [
                [
                    ['text' => CommandHelper::AUTO_RESPONDER_ON],
                ],
                [
                    ['text' => CommandHelper::BACK]
                ]
            ];
        } else {
            $keyboard = [
                [
                    ['text' => CommandHelper::AUTO_RESPONDER_OFF],
                ],
                [
                    ['text' => CommandHelper::BACK]
                ]
            ];
        }

        return new ReplyKeyboardMarkup(
            $keyboard,
            true,
            true
        );
    }
}