<?php

namespace Pcs\Bot\services\keyboard;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\UserRepository;

class AddingRedirectKeyboard
{
    public static function get($chatID)
    {
        $redirectRepository = new RedirectRepository();
        $userRepository = new UserRepository();

        $user = $userRepository->getUserByChatID($chatID);
        $redirect = $redirectRepository->getRedirectForUser($user);

        $keyboard = [
            [
                ["text" => CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER]
            ],
            [
                ["text" => CommandHelper::BACK]
            ]
        ];

        return $keyboard;
    }
}