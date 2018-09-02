<?php

namespace Pcs\Bot\services\keyboard;

use Pcs\Bot\helpers\CommandHelper;

class ManageRedirectsKeyboard
{
    public static function get($chatID)
    {
        if (!in_array($chatID, $GLOBALS['admins'])) {
            $keyboard = [
                [
                    ['text' => CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS],
                    ['text' => CommandHelper::ADDING_EXTENSION_REDIRECT],
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
                    ['text' => CommandHelper::BACK]
                ]
            ];
        }

        return $keyboard;
    }
}