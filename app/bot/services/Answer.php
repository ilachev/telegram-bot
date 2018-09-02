<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;
use TelegramBot\Api\Types\Message;

class Answer
{
    private $userRepository;
    private $chatRepository;
    private $sessionRepository;
    private $mappingRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->chatRepository = new ChatRepository();
        $this->sessionRepository = new SessionRepository();
        $this->mappingRepository = new MappingRepository();
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
                $this->sessionRepository->setStatus($chatID, SessionStatusHelper::START);

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

                $this->sessionRepository->setStatus($chatID, SessionStatusHelper::MANAGE_REDIRECTS);

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

            case CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS:

                $this->sessionRepository->setStatus($chatID, SessionStatusHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS);

                $mappings = $this->mappingRepository->getMappings();

                if (!empty($mappings)) {
                    $answer = '';
                    foreach ($mappings as $mapping) {
                        $answer .= $mapping['country'] . ' ' . $mapping['mapping'] . PHP_EOL;
                    }
                } else {
                    $answer = 'Направлений для переадресаций не найдено';
                }

                return $answer;
                break;

            case CommandHelper::ADDING_EXTENSION_REDIRECT:
                return 'Введите код страны и кол-во символов';
                break;

            case CommandHelper::BACK:

                $answer = ' ';
                $currentStatus = $this->sessionRepository->getStatus($chatID);

                if ($currentStatus == SessionStatusHelper::MANAGE_REDIRECTS) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS) {
                    $answer = 'Выберите пункт';
                }
                return $answer;
                break;

            default:
                return 'Команда не существует';
                break;
        }

        return 'Команда не существует';
    }
}