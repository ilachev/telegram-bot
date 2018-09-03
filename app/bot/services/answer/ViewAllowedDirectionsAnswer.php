<?php

namespace Pcs\Bot\services\answer;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\SessionRepository;

class ViewAllowedDirectionsAnswer
{
    public static function get($chatID)
    {
        $answer = '';
        $sessionRepository = new SessionRepository();
        $mappingRepository = new MappingRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS);

        $mappings = $mappingRepository->getMappings();

        if (!empty($mappings)) {
            foreach ($mappings as $mapping) {
                $answer .= $mapping['country'] . ' ' . $mapping['mapping'] . PHP_EOL;
            }
        } else {
            $answer = 'Направлений для переадресаций не найдено';
        }

        return $answer;
    }
}