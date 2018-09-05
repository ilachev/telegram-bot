<?php

namespace Pcs\Bot\services\answer;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class CreateRedirectNumberAnswer
{
    public static function get($chatID, $phone = null, $type = null)
    {
        $phoneIsAllowed = false;

        $sessionRepository = new SessionRepository();
        $mappingRepository = new MappingRepository();
        $redirectRepository = new RedirectRepository();
        $chatRepository = new ChatRepository();
        $userRepository = new UserRepository();

        if (!is_null($type)) {
            $user = $userRepository->getUserByChatID($chatID);

            if (!empty($user->phone)) {
                if ($redirectRepository->setRedirect($user->id, $user->phone)) {
                    $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS);
                    return 'Номер успешно установлен для переадресации';
                } else {
                    return 'Не удалось установить номер для переадресации';
                }
            }
        }

        $mappings = $mappingRepository->getMappings();

        if (!empty($mappings)) {
            foreach ($mappings as $mapping) {
                $mappingLength = iconv_strlen($mapping['mapping']);
                $phoneLength = iconv_strlen($phone);

                $mappingKnownDigits = explode('*', $mapping['mapping']);

                if ((stripos($phone, $mappingKnownDigits[0]) !== false) && $mappingLength == $phoneLength) {
                    $phoneIsAllowed = true;
                    break;
                }
            }

            if ($phoneIsAllowed == true) {

                $userID = $chatRepository->getUserIDByChatID($chatID);
                $redirect = $redirectRepository->updateRedirect($userID, $phone);

                if ($redirect == true) {
                    $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS);
                    return 'Номер успешно установлен для переадресации';
                }

                return 'Номер для переадресации уже такой';

            } else {
                return 'Данное направление запрещено для переадресации.';
            }
        } else {
            return 'Направлений для переадресаций не найдено';
        }
    }
}