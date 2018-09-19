<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AdminDeleteMappingAnswer
{
    public static function get($chatID, $session = null)
    {
        $sessionRepository = new SessionRepository();

        if (is_null($session)) {
            $sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_MAPPING);
            return 'Введите добавочный номер';
        } else {
            return 'Выберите пункт';
        }
    }
}