<?php

namespace pcs\bot\services;

use pcs\bot\repositories\DbQuery;
use pcs\helpers\CommandHelper;
use PDO;

class Answer
{
    public function getAnswer(PDO $db, $command, $message, $admins)
    {
        $dbQuery = new DbQuery($db);
        $chatID = $message->getChat()->getId();
        $messageText = $message->getText();
        $username = $message->getFrom()->getUsername();

        switch ($command) {
            case CommandHelper::START:
                return "Добро пожаловать, {$username} ". PHP_EOL
                    . "Данный бот предназначен для оповещения о пропущенных звонках по Вашему добавочному номеру." . PHP_EOL
                    . "Для включения оповещений нажмите кнопку <b>Подписаться</b> и согласитесь с передачей Вашего мобильного номера боту." . PHP_EOL
                    . "Вы можете отписаться от уведомлений нажав кнопку - <b>Отписаться</b>". PHP_EOL;
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

            case CommandHelper::ADDING_MAPPING:
                return 'Введите добавочный номер нового сотрудника';
                break;

            case CommandHelper::USER_MANAGEMENT:
                return 'Выберите пункт';
                break;

            default:
                return 'Команда не существует';
                break;
        }

        return 'Команда не существует';
    }
}