<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class AdminViewMappingAnswer
{
    public static function get($chatID)
    {
        $userRepository = new UserRepository();
        $sessionRepository = new SessionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::VIEW_MAPPING);

        $mappings = $userRepository->getMappings();

        if (!empty($mappings)) {
            $answer = 'Список сопоставлений' . PHP_EOL . PHP_EOL;

            foreach ($mappings as $mapping) {
                $answer .= 'Добавочный: ' . $mapping->user->extension->extension .
                    ' Сотрудник: ' . $mapping->user->full_name .
                    ' Мобильный: ' . $mapping->user->phone . PHP_EOL;
            }

            return $answer;
        } else {
            return 'Сопоставлений не найдено';
        }
    }
}