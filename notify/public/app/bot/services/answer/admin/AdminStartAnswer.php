<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\SessionRepository;

class AdminStartAnswer
{
    public static function get($chatID, $username)
    {
        $chatRepository = new ChatRepository();
        $sessionRepository = new SessionRepository();

        $chat = $chatRepository->getChatByChatID($chatID);

        if (!empty($chat->chat_id)) {
            $sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
            $sessionRepository->clearTempString($chatID);
            $answer = 'Выберите пункт';
        } else {
            $answer = "Добро пожаловать, {$username} ". PHP_EOL .
                "Данный бот предназначен для оповещения о пропущенных звонках по Вашему добавочному номеру." . PHP_EOL .
                "Для включения оповещений нажмите кнопку <b>Подписаться</b> и согласитесь с передачей Вашего мобильного номера боту." . PHP_EOL .
                "Вы можете отписаться от уведомлений нажав кнопку - <b>Отписаться</b>". PHP_EOL;
        }

        return $answer;
    }
}