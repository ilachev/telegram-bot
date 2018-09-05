<?php

namespace Pcs\Bot\services\answer;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class NotAdminAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::NOT_ADMIN);

        return 'Вы не администратор';
    }
}