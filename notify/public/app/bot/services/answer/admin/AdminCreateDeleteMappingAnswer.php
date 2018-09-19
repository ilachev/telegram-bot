<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\repositories\ExtensionRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class AdminCreateDeleteMappingAnswer
{
    public static function get($chatID, $message, $step)
    {
        $sessionRepository = new SessionRepository();
        $extensionRepository = new ExtensionRepository();
        $userRepository = new UserRepository();

        if ($step == 'first') {
            $mapping = $userRepository->getMappingByExtension($message);

            if (!empty($mapping)) {

                $sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_MAPPING_FIRST_STEP);
                $sessionRepository->saveTempString($chatID, $message);

                return 'Добавочный ' . $mapping->user->extension->extension .
                    ' сопоставлен с мобильным номером ' . $mapping->user->phone .
                    ' сотрудника ' . $mapping->user->full_name . PHP_EOL .
                    'Удалить?';
            }
            return 'Добавочный номер ' . $message . ' не сопоставлен ни с одним из мобильных номеров';
        } elseif ($step == 'second') {
            $extension = $sessionRepository->getTempString($chatID);

            if (!empty($extension)) {
                if ($extensionRepository->deleteExtension($extension)) {
                    $sessionRepository->clearTempString($chatID);
                    $sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_MAPPING_SECOND_STEP);

                    return 'Сопоставление успешно удалено';
                } else {
                    return 'Ошибка при удалении сопоставления';
                }
            }
        }
        return 'Непредвиденная ошибка';
    }
}