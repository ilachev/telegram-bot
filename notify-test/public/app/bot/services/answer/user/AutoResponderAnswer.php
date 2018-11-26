<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\AutoResponderStatusRepository;
use Pcs\Bot\repositories\SessionRepository;

class AutoResponderAnswer
{
    public static function get($chatId)
    {
        $sessionRepository = new SessionRepository();
        $responderRepository = new AutoResponderStatusRepository();

        $sessionRepository->setStatus($chatId, SessionStatusHelper::AUTO_RESPONDER);
        $status = $responderRepository->getStatusForCurrentUser($chatId);

        if ($status == 0) {
            return 'У вас выключен автоответчик';
        }
        return 'У вас включен автоответчик';
    }
}