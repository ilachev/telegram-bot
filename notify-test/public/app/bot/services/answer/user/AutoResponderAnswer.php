<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AutoResponderAnswer
{
    public static function get($chatId)
    {
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatId, SessionStatusHelper::AUTO_RESPONDER);

        return 'У вас включен автоответчик';
    }
}