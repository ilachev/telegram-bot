<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AdminAddingDirectionsAnswer
{
    public static function get($chatID, $session = null)
    {
        $sessionRepository = new SessionRepository();

        if (is_null($session)) {
            $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_DIRECTIONS);
        }

        return 'Введите код страны и кол-во символов  (Например "380*********")';
    }
}