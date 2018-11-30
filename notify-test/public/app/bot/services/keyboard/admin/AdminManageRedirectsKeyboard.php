<?php

namespace Pcs\Bot\services\keyboard\admin;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\UserRepository;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AdminManageRedirectsKeyboard
{
    public static function get($chatID)
    {
        $userRepository = new UserRepository();
        $redirectRepository = new RedirectRepository();

        $user = $userRepository->getUserByChatID($chatID);
        $redirect = $redirectRepository->getRedirectForUser($user->id);

        if (!empty($redirect)) {
            $keyboard = [
                [
                    ['text' => CommandHelper::ADDING_DIRECTIONS],
                    ['text' => CommandHelper::DELETING_DIRECTIONS],
                ],
                [
                    ['text' => CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS],
                    ['text' => CommandHelper::ADDING_REDIRECT],
                ],
                [
                    ['text' => CommandHelper::DELETE_REDIRECT]
                ],
                [
                    ['text' => CommandHelper::BACK]
                ]
            ];
        } else {
            $keyboard = [
                [
                    ['text' => CommandHelper::ADDING_DIRECTIONS],
                    ['text' => CommandHelper::DELETING_DIRECTIONS],
                ],
                [
                    ['text' => CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS],
                    ['text' => CommandHelper::ADDING_REDIRECT],
                ],
                [
                    ['text' => CommandHelper::BACK]
                ]
            ];
        }

        return new ReplyKeyboardMarkup(
            $keyboard,
            true,
            true
        );
    }
}