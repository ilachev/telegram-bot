<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class ManageRedirectsAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::MANAGE_REDIRECTS);

        return 'Выберите пункт';
    }
}