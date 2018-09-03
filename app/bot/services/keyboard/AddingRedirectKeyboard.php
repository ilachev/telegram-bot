<?php

namespace Pcs\Bot\services\keyboard;

use Pcs\Bot\helpers\CommandHelper;

class AddingRedirectKeyboard
{
    public static function get()
    {
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