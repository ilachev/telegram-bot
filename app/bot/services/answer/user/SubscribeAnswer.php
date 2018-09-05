<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\UserRepository;
use TelegramBot\Api\Types\Message;

class SubscribeAnswer
{
    public static function get(Message $message)
    {
        $userRepository = new UserRepository();
        $chatRepository = new ChatRepository();

        $phoneNumber = $message->getContact()->getPhoneNumber();

        if (!empty($phoneNumber) && stripos($phoneNumber, '+') !== false) {
            $phoneNumber = str_replace('+', '', $phoneNumber);
        }

        $user = $userRepository->getUserByPhone($phoneNumber);

        if (!empty($user->extension)) {

            $chatRepository->saveChatID(
                $message->getChat()->getId(),
                $user->id
            );

            $answer = "Вы успешно подписались на оповещения о пропущенных звонках на номер {$user->extension}". PHP_EOL .
                "Если это не ваш номер - обратитесь на Хотлайн";
        } else {
            $answer = "Данный номер мобильного телефона не занесен в базу данных сотрудников. Обратитесь на Хотлайн.";
        }

        return $answer;
    }
}