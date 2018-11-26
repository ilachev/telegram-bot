<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\AutoResponderStatusRepository;
use Pcs\Bot\repositories\SessionRepository;

class AutoResponderOffAnswer
{
    public static function get($chatId)
    {
        $statusRepository = new AutoResponderStatusRepository();
        $sessionRepository = new SessionRepository();

        $statusRepository->setStatus($chatId, CommandHelper::AUTO_RESPONDER_OFF_NUMBER);
        $sessionRepository->setStatus($chatId, SessionStatusHelper::AUTO_RESPONDER_OFF);

        return 'Автоответчик отключен';
    }
}