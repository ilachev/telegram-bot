<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\SessionRepository;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Keyboard
{
    private $chatRepository;
    private $sessionRepository;

    public function __construct()
    {
        $this->chatRepository = new ChatRepository();
        $this->sessionRepository = new SessionRepository();
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

                if (!in_array($chatID, $GLOBALS['admins'])) {
                    $keyboard = [
                        [
                            ['text' => CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS],
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

                return new ReplyKeyboardMarkup(
                    $keyboard,
                    true,
                    true
                );
                break;

            case CommandHelper::BACK:

                $keyboard = [];

                $currentStatus = $this->sessionRepository->getStatus($chatID);

                if ($currentStatus == 4) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::START);

                    $keyboard = [
                        [
                            ["text" => CommandHelper::MANAGE_REDIRECTS]
                        ]
                    ];
                }

                return new ReplyKeyboardMarkup(
                    $keyboard,
                    true,
                    true
                );
                break;

            default:
                return null;
                break;
        }
    }
}