<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AdminManageRedirectsAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_MANAGE_REDIRECTS);

        return 'Выберите пункт';
    }
}