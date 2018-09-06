<?php

namespace Pcs\Bot\services\answer\admin;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\SessionRepository;

class AdminCreateAddingDirectionAnswer
{
    public static function get($chatID, $message, $step)
    {
        $sessionRepository = new SessionRepository();
        $mappingRepository = new MappingRepository();

        if ($step == 'first') {
            $mapping = $mappingRepository->getMappingByMask($message);

            if (!empty($mapping)) {
                return 'Направление уже существует' . PHP_EOL .
                    $mapping->country . ' ' . $mapping->mapping;
            }

            if ($sessionRepository->saveTempString($chatID, $message)) {
                $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_DIRECTION_FIRST_STEP);
                return 'Введите название направления';
            }
            return 'Не удалось сохранить маску';
        } elseif ($step == 'second') {
            $mapping = $sessionRepository->getTempString($chatID);
            if (!empty($mapping)) {
                if ($mappingRepository->saveMapping($message, $mapping)) {
                    $sessionRepository->clearTempString($chatID);
                    $sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_DIRECTION_SECOND_STEP);

                    return 'Новое направление успешно добавлено';
                } else {
                    return 'Ошибка при добавлении направления';
                }
            }
        }
        return 'Непредвиденная ошибка';
    }
}