<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Keyboard
{
    public function getKeyboard(Message $message, $command = null)
    {
        if ($command == null) {
            $command = $message->getText();
        }

        switch ($command) {
            case CommandHelper::START:
                return new ReplyKeyboardMarkup(
                    [
                        [
                            ["text" => CommandHelper::SUBSCRIBE, 'request_contact' => true]
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
                            ['text' => CommandHelper::MANAGE_REDIRECTS],
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
                            ['text' => CommandHelper::VIEW_MAPPING],
                            ['text' => CommandHelper::ADDING_MAPPING],
                        ],
                        [
                            ['text' => CommandHelper::EDITING_MAPPING],
                            ['text' => CommandHelper::DELETING_MAPPING],
                        ]
                    ],
                    true,
                    true
                );
                return $keyboard;
                break;

            case CommandHelper::MANAGE_REDIRECTS:
                $keyboard = new ReplyKeyboardMarkup(
                    [
                        [
                            ['text' => CommandHelper::ADDING_DIRECTIONS],
                            ['text' => CommandHelper::DELETING_DIRECTIONS],
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