<?php

namespace Pcs\Bot\services;

use Pcs\Bot\Logger;
use Pcs\Bot\Models\Database;
use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\UserRepository;
use TelegramBot\Api\Types\Message;

class Answer
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
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
                return "Добро пожаловать, {$username} ". PHP_EOL .
                    "Данный бот предназначен для оповещения о пропущенных звонках по Вашему добавочному номеру." . PHP_EOL .
                    "Для включения оповещений нажмите кнопку <b>Подписаться</b> и согласитесь с передачей Вашего мобильного номера боту." . PHP_EOL .
                    "Вы можете отписаться от уведомлений нажав кнопку - <b>Отписаться</b>". PHP_EOL;
                break;

            case CommandHelper::SUBSCRIBE:
                return "Вы успешно подписались на оповещения о пропущенных звонках на номер {$message->getContact()->getLastName()}". PHP_EOL .
                    " Если это не ваш номер - обратитесь на Хотлайн";
                break;

            case CommandHelper::ADMIN:
                if (in_array($chatID, $admins)) {
                    return "/list - просмотр списка подписок" . PHP_EOL
                        . "/ulist - просотр списка сопоставлений" . PHP_EOL
                        . "/uset {internal} {mobile}- Задать сопоставление внутренний-номер" . PHP_EOL
                        . "/unset {internal} {mobile} - Удалить сопоставление и подписку" . PHP_EOL
                    ;
                } else {
                    return 'Нет доступа';
                }
                break;

            case CommandHelper::LIST:
                if (in_array($chatID, $admins)) {
                    $reply	 = "Список подписок" . PHP_EOL;
                    $numbers = $dbQuery->getNumbers();

                    foreach ($numbers as $number) {
                        $reply .= "Внутренний: " . $number['number'] . ' Пользователь: @' . $number['name'] . ' [' . $number['tg_number'] . ']' . PHP_EOL;
                    }
                    return $reply;
                }
                break;

            case CommandHelper::ULIST:
                if (in_array($chatID, $admins)) {
                    $reply	 = "Список сопоставлений" . PHP_EOL;
                    $numbers = $dbQuery->getAllNumbers();

                    foreach ($numbers as $number) {
                        $reply .= "Внтурений: " . $number['internal'] . ' Мобильный: ' . $number['tg_number'] . PHP_EOL;
                    }
                    return $reply;
                }
                break;

            case CommandHelper::USET:
                if (in_array($chatID, $admins)) {
                    $cmd_args = explode(' ', $messageText);
                    $reply = "Неверные параметры";
                    if (count($cmd_args) == 3 && is_numeric($cmd_args[1])) {
                        if ($db->exec('insert ignore into numbers(internal,tg_number) values(' . $cmd_args[1] . ',' . $db->quote($cmd_args[2]) . ');')) {
                            $reply = 'Успешно';
                        }
                    }
                    return $reply;
                }
                break;

            case CommandHelper::UNSET:
                if (in_array($chatID, $admins)) {
                    $cmd_args = explode(' ', $messageText);
                    $reply = "Неверные параметры";
                    if (count($cmd_args) == 3 && is_numeric($cmd_args[1])) {
                        if ($db->exec('delete from numbers where internal=' . $cmd_args[1] . ' and tg_number=' . $db->quote($cmd_args[2]))) {
                            $db->exec('delete from subs where number=' . $cmd_args[1]);
                            $reply = 'Успешно';
                        }
                    }
                    return $reply;
                }
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