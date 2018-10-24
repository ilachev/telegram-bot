<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\SessionRepository;

class AdminCreateDeleteDirectionAnswer
{
    public static function get($chatID, $message, $step)
    {
        $sessionRepository = new SessionRepository();
        $mappingRepository = new MappingRepository();

        if ($step == 'first') {
            $mapping = $mappingRepository->getMappingByMask($message);

            if (!empty($mapping)) {

                $sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_DIRECTIONS_FIRST_STEP);
                $sessionRepository->saveTempString($chatID, $message);

                return 'Заданная маска разрешает направление переадресации' . PHP_EOL .
                    $mapping->country . ' ' . $mapping->mapping . PHP_EOL .
                    'Хотите удалить?';
            }

            return 'Направление не найдено';
        } elseif ($step == 'second') {
            $mapping = $sessionRepository->getTempString($chatID);
            if (!empty($mapping)) {

                if ($mappingRepository->deleteMapping($mapping)) {
                    $sessionRepository->clearTempString($chatID);
                    $sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_DIRECTIONS_SECOND_STEP);

                    return 'Направление успешно удалено';
                } else {
                    return 'Ошибка при удалении направления';
                }
            }
        }
        return 'Непредвиденная ошибка';
    }
}