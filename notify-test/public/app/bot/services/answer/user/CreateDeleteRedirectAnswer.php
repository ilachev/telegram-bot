<?php

namespace Pcs\Bot\services\answer\user;

use Pcs\Asterisk\AsteriskService;
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
        $asteriskService = new AsteriskService();


        $user = $userRepository->getUserByChatID($chatId);
        $redirectRepository->deleteRedirect($user->id);
        $asteriskService->deleteRedirect($user->extension->extension);

        $sessionRepository->setStatus($chatId, SessionStatusHelper::DELETE_REDIRECT_YES);

        return 'Переадресация отменена';
    }
}