<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;

class AddingRedirectAnotherNumberAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER);

        $answer = 'Введите номер для переадресации в международном формате' . PHP_EOL .
            '(Пример 380501234567 или 79261234567 без +)';

        return $answer;
    }
}