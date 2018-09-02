<?php

namespace Pcs\Bot\services;

use Pcs\Bot\Logger;
use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\UserRepository;
use TelegramBot\Api\Types\Message;

class Answer
{
    private $userRepository;
    private $chatRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->chatRepository = new ChatRepository();
    }

    public function getAnswer(Message $message, $command = null)
    {
        if ($command == null) {
            $command = $message->getText();
        }

        $chatID = $message->getChat()->getId();
        $username = $message->getFrom()->getUsername();

        switch ($command) {
            case CommandHelper::START:

                $chat = $this->chatRepository->getChatByChatID($chatID);

                if (!empty($chat->chat_id)) {
                    $answer = 'Выберите пункт';
                } else {
                    $answer = "Добро пожаловать, {$username} ". PHP_EOL .
                        "Данный бот предназначен для оповещения о пропущенных звонках по Вашему добавочному номеру." . PHP_EOL .
                        "Для включения оповещений нажмите кнопку <b>Подписаться</b> и согласитесь с передачей Вашего мобильного номера боту." . PHP_EOL .
                        "Вы можете отписаться от уведомлений нажав кнопку - <b>Отписаться</b>". PHP_EOL;
                }

                return $answer;
                break;

            case CommandHelper::SUBSCRIBE:

                $phoneNumber = $message->getContact()->getPhoneNumber();

                if (!empty($phoneNumber) && stripos($phoneNumber, '+') !== false) {
                    $phoneNumber = str_replace('+', '', $phoneNumber);
                }

                $user = $this->userRepository->getUserByPhone($phoneNumber);

                if (!empty($user->extension)) {

                    $this->chatRepository->saveChatID(
                        $message->getChat()->getId(),
                        $user->id
                    );

                    $answer = "Вы успешно подписались на оповещения о пропущенных звонках на номер {$user->extension}". PHP_EOL .
                        "Если это не ваш номер - обратитесь на Хотлайн";
                } else {
                    $answer = "Данный номер мобильного телефона не занесен в базу данных сотрудников. Обратитесь на Хотлайн.";
                }

                return $answer;
                break;

            case CommandHelper::USER_MANAGEMENT:
                return 'Выберите пункт';
                break;

            case CommandHelper::MANAGE_REDIRECTS:
                return 'Выберите пункт';
                break;

            case CommandHelper::ADDING_MAPPING:
                return 'Введите добавочный номер нового сотрудника';
                break;

            case CommandHelper::DELETING_MAPPING:
                return 'Введите добавочный номер';
                break;

            case CommandHelper::EDITING_MAPPING:
                return 'Введите добавочный номер';
                break;

            case CommandHelper::ADDING_DIRECTIONS:
                return 'Введите код страны и кол-во символов  (Например 380*********)';
                break;

            case CommandHelper::DELETING_DIRECTIONS:
                return 'Введите код страны и кол-во символов';
                break;

            default:
                return 'Команда не существует';
                break;
        }

        return 'Команда не существует';
    }
}