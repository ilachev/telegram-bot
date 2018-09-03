<?php

namespace Pcs\Bot\services\answer;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class AddingRedirectAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();
        $userRepository = new UserRepository();
        $redirectRepository = new RedirectRepository();
        $mappingRepository = new MappingRepository();

        $answer = '';

        $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_EXTENSION_REDIRECT);

        $user = $userRepository->getUserByChatID($chatID);

        if (!empty($user->phone)) {

            $redirect = $redirectRepository->getRedirectForUser($user->id);

            if (!empty($redirect)) {

                $answer = $user->full_name . ', у вас установлена переадресация на номер ' . $redirect . '.';

            } else {

                $mappings = $mappingRepository->getMappings();

                if (!empty($mappings)) {

                    foreach ($mappings as $mapping) {
                        $mappingLength = iconv_strlen($mapping['mapping']);
                        $phoneLength = iconv_strlen($user->phone);

                        $mappingKnownDigits = explode('*', $mapping['mapping']);

                        if ((stripos($user->phone, $mappingKnownDigits[0]) !== false) && $mappingLength == $phoneLength) {
                            $answer = $user->full_name . ', ваш мобильный номер ' . $user->phone . '. Установить на него переадресацию звонков?';
                            break;
                        }

                        $answer = 'asdasd';
                    }
                } else {
                    $answer = 'Направлений для переадресаций не найдено';
                }
            }

        } else {
            $answer = 'Данный номер мобильного телефона не занесен в базу данных сотрудников. Обратитесь на Хотлайн.';
        }

        return $answer;
    }
}