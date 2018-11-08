<?php

namespace Pcs\Bot\services\keyboard\admin;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\ChatRepository;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AdminStartKeyboard
{
    public static function get($chatID)
    {
        $chatRepository = new ChatRepository();

        $chat = $chatRepository->getChatByChatID($chatID);

        if (!empty($chat->chat_id)) {
            $keyboard = [
                [
                    ["text" => CommandHelper::USER_MANAGEMENT],
                    ["text" => CommandHelper::MANAGE_REDIRECTS],
                ],
                [
                    ["text" => CommandHelper::UNSUBSCRIBE]
                ]
            ];
        } else {
            $keyboard = [
                [
                    ["text" => CommandHelper::SUBSCRIBE, 'request_contact' => true]
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