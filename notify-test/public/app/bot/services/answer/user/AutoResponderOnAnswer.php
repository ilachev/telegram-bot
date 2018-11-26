<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\AutoResponderStatusRepository;
use Pcs\Bot\repositories\SessionRepository;

class AutoResponderOnAnswer
{
    public static function get($chatId)
    {
        $statusRepository = new AutoResponderStatusRepository();
        $sessionRepository = new SessionRepository();

        $statusRepository->setStatus($chatId, CommandHelper::AUTO_RESPONDER_ON_NUMBER);
        $sessionRepository->setStatus($chatId, SessionStatusHelper::AUTO_RESPONDER_ON);

        return 'Автоответчик включён.' . PHP_EOL .
            'Если вы в течении ХХ секунд не ответили на входящий вызов, то вызов будет отправлен на автоответчик';
    }
}