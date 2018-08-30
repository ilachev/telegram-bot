<?php

namespace pcs\bot\services;

use pcs\helpers\CommandHelper;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Keyboard
{
    public function getKeyboard($command)
    {
        switch ($command) {
            case CommandHelper::START:
                return new ReplyKeyboardMarkup(
                    [
                        [
                            ["text" => "Подписаться", 'request_contact' => true]
                        ]
                    ],
                    true,
                    true
                );
                break;

            case CommandHelper::ADMIN:
                $keyboard = new ReplyKeyboardMarkup(
                    [
                        [
                            ['text' => CommandHelper::USER_MANAGEMENT],
                        ]
                    ],
                    true,
                    true
                );
                return $keyboard;
                break;

            case CommandHelper::USER_MANAGEMENT:
                $keyboard = new ReplyKeyboardMarkup(
                    [
                        [
                            ['text' => CommandHelper::ADDING_MAPPING],
                        ]
                    ],
                    true,
                    true
                );
                return $keyboard;
                break;

            default:
                return null;
                break;
        }
    }
}