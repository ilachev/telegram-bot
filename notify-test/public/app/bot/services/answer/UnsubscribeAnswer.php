<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 08.11.18
 * Time: 13:29
 */

namespace Pcs\Bot\services\answer;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\AutoResponderStatusRepository;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\SessionRepository;

class UnsubscribeAnswer
{
    public static function get($chatID)
    {
        $sessionRepository = new SessionRepository();
        $chatRepository = new ChatRepository();
        $statusRepository = new AutoResponderStatusRepository();

        $sessionRepository->setStatus($chatID, SessionStatusHelper::DEFAULT);
        $statusRepository->setStatus($chatID, CommandHelper::AUTO_RESPONDER_OFF_NUMBER);

        $answer = '';
        if ($chatRepository->deleteChat($chatID)) {
            $answer = 'Вы успешно отписались от оповещений о пропущенных звонках и автоответчика.  Если была настроена переадресация, то она продолжает функционировать';
        }
        return $answer;
    }
}