<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AdminEditingMappingAnswer
{
    public static function get($chatID, $session = null)
    {
        $sessionRepository = new SessionRepository();

        if (is_null($session)) {
            $sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING);
        } else {
            $sessionRepository->clearTempString($chatID);
        }

        return 'Введите добавочный номер';
    }
}