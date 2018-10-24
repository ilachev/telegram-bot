<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\ExtensionRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class AdminViewMappingAnswer
{
    public static function get($chatID)
    {
        $userRepository = new UserRepository();
        $sessionRepository = new SessionRepository();
        $extensionRepository = new ExtensionRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::VIEW_MAPPING);

        $extensions = $extensionRepository->getExtensions();

        $data = [];
        if (!empty($extensions)) {
            $mappings = $userRepository->getMappings();

            $answer = 'Список сопоставлений' . PHP_EOL . PHP_EOL;

            $tempKey = 0;
            $data[$tempKey] = $answer;
            foreach ($mappings as $key => $mapping) {
                $answer = 'Добавочный: ' . $mapping->user->extension->extension .
                    ' Сотрудник: ' . $mapping->user->full_name .
                    ' Мобильный: ' . $mapping->user->phone . PHP_EOL;

                if (strlen($answer . $data[$tempKey]) > 4000) {
                    $tempKey++;
                }
                $data[$tempKey] .= $answer;
            }

            if (count($data) == 1) {
                return $data[0];
            } else {
                return $data;
            }

        } else {
            return 'Сопоставлений не найдено';
        }
    }
}