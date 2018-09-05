<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AdminAddingDirectionsAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_DIRECTIONS);

        return 'Введите название страны, код страны и кол-во символов  (Например "Украина 380*********")';
    }
}