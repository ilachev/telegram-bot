<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 08.11.18
 * Time: 13:29
 */

namespace Pcs\Bot\services\answer;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\SessionRepository;

class UnsubscribeAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();
        $chatRepository = new ChatRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::DEFAULT);

        $answer = '';
        if ($chatRepository->deleteChat($chatID)) {
            $answer = 'Вы успешно отписались от оповещений';
        }
        return $answer;
    }
}