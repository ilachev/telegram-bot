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
            $mapping = $userRepository->getMappingByExtension($message);

            if (!empty($mapping->user->extension->extension)) {

                $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING_ALREADY_HAVE);

                return 'Добавочный ' . $mapping->user->extension->extension . ' закреплён за сотрудником ' . $mapping->user->full_name . ' c номером ' . $mapping->user->phone . PHP_EOL .
                    'Для нового сопоставления необходимо удалить уже существующее сопоставление.' . PHP_EOL .
                    'Удалить?';

            } else {
                if (is_null($message)) {
                    $string = $sessionRepository->getTempString($chatID);
                    $data = json_decode($string);
                    $sessionRepository->saveTempString($chatID, $data[0]);
                } else {
                    $sessionRepository->saveTempString($chatID, $message);
                }
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
                $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING_SECOND_STEP);
                return 'Введите ФИО сотрудника';
            }
        } elseif ($step == 'third') {
            $string = $sessionRepository->getTempString($chatID);
            $data = json_decode($string);

            $userRepository->saveUserWithExtension($data[0], $data[1], $message);
            $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING_THIRD_STEP);
            $sessionRepository->clearTempString($chatID);

            return 'Сопоставление успешно задано';
        }
        return 'Не предвиденная ошибка';
    }
}