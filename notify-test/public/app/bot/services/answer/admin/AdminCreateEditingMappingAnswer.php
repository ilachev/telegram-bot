<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class AdminCreateEditingMappingAnswer
{
    public static function get($chatID, $message, $step)
    {
        $sessionRepository = new SessionRepository();
        $userRepository = new UserRepository();

        if ($step == 'first') {
            $mapping = $userRepository->getMappingByExtension($message);

            if (empty($mapping->user->extension->extension)) {
                $sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING_NOT_HAVE);

                return 'Добавочный номер ' . $message . ' не сопоставлен ни с одним из мобильных номеров.' . PHP_EOL .
                    'Установить сопоставление?';
            }

            $sessionRepository->saveTempString($chatID, $message);
            $sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING_FIRST_STEP);

            return 'Добавочный номер ' . $mapping->user->extension->extension .
                ' сопоставлен с мобильным номером ' . $mapping->user->phone .
                ' сотрудника ' . $mapping->user->full_name . PHP_EOL .
                'Редактировать?';
        } elseif ($step == 'second') {

            if ($message == 'back') {
                $string = $sessionRepository->getTempString($chatID);
                $data = json_decode($string);
                $sessionRepository->saveTempString($chatID, $data[0]);
            }

            $sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING_SECOND_STEP);

            return 'Введите номер мобильного телефона в формате 79********* или 380*********';

        } elseif ($step == 'third') {
            $extension = $sessionRepository->getTempString($chatID);

            if (!empty($extension)) {
                $data = [
                    $extension,
                    $message
                ];
                $string = json_encode($data);
                $sessionRepository->saveTempString($chatID, $string);
                $sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING_THIRD_STEP);
                return 'Введите ФИО сотрудника';
            }
        } elseif ($step == 'fourth') {
            $string = $sessionRepository->getTempString($chatID);
            $data = json_decode($string);

            $userRepository->editUserWithExtension($data[0], $data[1], $message);
            $sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING_FOURTH_STEP);
            $sessionRepository->clearTempString($chatID);

            return 'Сопоставление успешно задано';
        }
        return 'Непредвиденная ошибка';
    }
}