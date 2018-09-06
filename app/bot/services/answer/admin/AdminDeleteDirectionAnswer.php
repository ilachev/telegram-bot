<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AdminDeleteDirectionAnswer
{
    public static function get($chatID, $session = null)
    {
        $sessionRepository = new SessionRepository();

        if (is_null($session)) {
            $sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_DIRECTIONS);
        }

        return 'Введите код страны и кол-во символов  (Например "380*********")';
    }
}