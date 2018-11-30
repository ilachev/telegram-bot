<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;

class CreateDeleteRedirectAnswer
{
    public static function get($chatId)
    {
        $sessionRepository = new SessionRepository();
        $userRepository = new UserRepository();
        $redirectRepository = new RedirectRepository();


        $user = $userRepository->getUserByChatID($chatId);
        $redirectRepository->deleteRedirect($user->id);

        $sessionRepository->setStatus($chatId, SessionStatusHelper::DELETE_REDIRECT_YES);

        return 'Переадресация отменена';
    }
}