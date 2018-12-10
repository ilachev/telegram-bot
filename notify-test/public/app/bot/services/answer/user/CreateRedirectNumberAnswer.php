<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Asterisk\AsteriskService;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
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

        $asteriskService = new AsteriskService();

        if (!is_null($type)) {
            $user = $userRepository->getUserByChatID($chatID);

            if (!empty($user->phone)) {
                if ($asteriskService->updateRedirect($user->extension->extension, $user->phone)) {
                    if ($redirectRepository->setRedirect($user->id, $user->phone)) {
                        $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS);
                        return 'Номер успешно установлен для переадресации';
                    } else {
                        return 'Не удалось установить номер для переадресации';
                    }
                } else {
                    return 'Не удалось установить номер для переадресации';
                }
            }
            return 'Не удалось установить номер для переадресации';
        }

        $mappings = $mappingRepository->getMappings();

        if (!empty($mappings)) {
            foreach ($mappings as $mapping) {
                $mappingLength = iconv_strlen($mapping['mapping']);
                $phoneLength = iconv_strlen($phone);

                $mappingKnownDigits = explode('*', $mapping['mapping']);

                if(preg_match('/\d/', $mapping['mapping']) && (stripos($phone, $mappingKnownDigits[0]) !== false) && $mappingLength == $phoneLength){
                    $phoneIsAllowed = true;
                    break;

                }elseif(!preg_match('/\d/', $mapping['mapping']) && $mappingLength == $phoneLength){
                    $phoneIsAllowed = true;
                    break;

                }
            }

            if ($phoneIsAllowed == true) {
                $user = $userRepository->getUserByChatID($chatID);
                $userID = $chatRepository->getUserIDByChatID($chatID);

                if ($asteriskService->updateRedirect($user->extension->extension, $phone)) {
                    $redirect = $redirectRepository->updateRedirect($userID, $phone);

                    if ($redirect == true) {
                        $asteriskService->updateRedirect($user->extension->extension, $phone);
                        $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS);
                        return 'Номер успешно установлен для переадресации';
                    }
                }

                return 'Номер для переадресации уже такой';

            } else {
		
                return ' Данное направление запрещено для переадресации.';
            }
        } else {
            return 'Направлений для переадресаций не найдено';
        }
    }
}