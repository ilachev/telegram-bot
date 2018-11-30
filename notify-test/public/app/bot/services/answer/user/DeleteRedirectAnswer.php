<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class DeleteRedirectAnswer
{
    public static function get($chatId)
    {
        $sessionRepository = new SessionRepository();
        $sessionRepository->setStatus($chatId, SessionStatusHelper::DELETE_REDIRECT);

        return 'Вы действительно хотите отменить переадресацию?';
    }
}