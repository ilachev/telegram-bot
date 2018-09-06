<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\repositories\ExtensionRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class AdminCreateAddingMappingAnswer
{
    public static function get($chatID, $message, $step)
    {
        $sessionRepository = new SessionRepository();
        $extensionRepository = new ExtensionRepository();
        $userRepository = new UserRepository();

        if ($step == 'first') {
            $extension = $extensionRepository->getExtensionByExtension($message);

            if (!empty($extension)) {
                return '123';
            } else {
                if (is_null($message)) {
                    $string = $sessionRepository->getTempString($chatID);
                    Logger::log('sdad', json_decode($string));
                }
                $sessionRepository->saveTempString($chatID, $message);
                $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING_FIRST_STEP);
                return 'Введите номер мобильного телефона';
            }
        } elseif ($step == 'second') {
            $extension = $sessionRepository->getTempString($chatID);

            if (!empty($extension)) {
                $data = [
                    $extension,
                    $message
                ];
                $string = json_encode($data);
                $sessionRepository->saveTempString($chatID, $string);
                $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING_FIRST_STEP);
                return 'Введите ФИО сотрудника';
            }
        }
        return 'Не предвиденная ошибка';
    }
}