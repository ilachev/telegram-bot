<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\ChatRepository;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Keyboard
{
    private $chatRepository;

    public function __construct()
    {
        $this->chatRepository = new ChatRepository();
    }

    public function getKeyboard(Message $message, $command = null)
    {
        if ($command == null) {
            $command = $message->getText();
        }

        $chatID = $message->getChat()->getId();

        switch ($command) {
            case CommandHelper::START:

                $chat = $this->chatRepository->getChatByChatID($chatID);

                if (!empty($chat->chat_id)) {
                    $keyboard = [
                        ["text" => CommandHelper::MANAGE_REDIRECTS]
                    ];
                } else {
                    $keyboard = [
                        ["text" => CommandHelper::SUBSCRIBE, 'request_contact' => true]
                    ];
                }

                return new ReplyKeyboardMarkup(
                    [
                        $keyboard
                    ],
                    true,
                    true
                );
                break;

            case CommandHelper::SUBSCRIBE:
                return new ReplyKeyboardMarkup(
                    [
                        [
                            ["text" => CommandHelper::MANAGE_REDIRECTS]
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