<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\SessionRepository;

class StartAnswer
{
    public static function get($chatID, $username)
    {
        $chatRepository = new ChatRepository();
        $sessionRepository = new SessionRepository();

        $chat = $chatRepository->getChatByChatID($chatID);

        $sessionRepository->setStatus($chatID, SessionStatusHelper::START);
        $sessionRepository->clearTempString($chatID);

        if (!empty($chat->chat_id)) {
            $answer = 'Выберите пункт';
        } else {
            $answer = "Добро пожаловать, {$username} ". PHP_EOL .
                "Данный бот предназначен для оповещения о пропущенных звонках по Вашему добавочному номеру." . PHP_EOL .
                "Для включения оповещений нажмите кнопку <b>Подписаться</b> и согласитесь с передачей Вашего мобильного номера боту.";
        }

        return $answer;
    }
}