<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\repositories\SessionRepository;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class BotHandler
{
    private $sessionRepository;

    public function __construct()
    {
        $this->sessionRepository = new SessionRepository();
    }

    public function on($notify = false, $chat = false, $answer = false, $voiceFilePath = false)
    {
        try {
            $bot = new Client(API_KEY);

            /**
             * @var BotApi $bot
             */

            if (!empty(PROXY_STRING)) {
                $bot->setProxy(PROXY_STRING);
            }

            if ($notify === true) {

                $bot->sendMessage(
                    $chat,
                    $answer,
                    'html',
                    false,
                    null,
                    null,
                    false
                );

            } elseif ($voiceFilePath !== false) {

                $bot->sendMessage(
                    $chat,
                    $answer,
                    'html',
                    false,
                    null,
                    null,
                    false
                );

                $voiceFile = new \CURLFile('/tmp/tmp.bZVbL6br0H/stream.part3-opus.ogg');

                $bot->sendVoice($chat, $voiceFile);

            } else {
                $bot->command(CommandHelper::START, function ($message) use ($bot) {
                    /**
                     * @var Message $message
                     */

                    $answer = new Answer();
                    $keyboard = new Keyboard();

                    $chatID = $message->getChat()->getId();

                    $bot->sendMessage(
                        $chatID,
                        $answer->getAnswer($message, CommandHelper::START),
                        'html',
                        false,
                        null,
                        $keyboard->getKeyboard($message, CommandHelper::START)
                    );
                });

                $bot->on(function(Update $update) use ($bot) {

                    if (!is_null($update)) {

                        $answer = new Answer();
                        $keyboard = new Keyboard();

                        $message = $update->getMessage();

                        if (!is_null($message)) {

                            if (!is_null($message->getChat())) {

                                $chatID = $message->getChat()->getId();
                                $messageText = $message->getText();

                                if ($messageText == CommandHelper::VIEW_MAPPING) {
                                    $ans = $answer->getAnswer($message);
                                    if (is_array($ans)) {
                                        foreach ($ans as $an) {
                                            $bot->sendMessage(
                                                $chatID,
                                                $an,
                                                'html',
                                                false,
                                                null,
                                                $keyboard->getKeyboard($message)
                                            );
                                        }
                                    } else {
                                        $bot->sendMessage(
                                            $chatID,
                                            $ans,
                                            'html',
                                            false,
                                            null,
                                            $keyboard->getKeyboard($message)
                                        );
                                    }
                                } else {
                                    $bot->sendMessage(
                                        $chatID,
                                        $answer->getAnswer($message),
                                        'html',
                                        false,
                                        null,
                                        $keyboard->getKeyboard($message)
                                    );
                                }
                            }
                        }
                    }

                }, function(Update $update) use ($bot) {
                    if (is_null($update)) {
                        return true;
                    }

                    $message = $update->getMessage();

                    if (is_null($message)) {
                        return true;
                    }

                    if (is_null($message->getChat())) {
                        return true;
                    } else {
                        $chatID = $message->getChat()->getId();
                    }

                    if (is_null($message->getContact())) {
                        return true;
                    } else {
                        if (is_null($message->getContact()->getPhoneNumber())) {
                            return true;
                        } else {
                            $answer = new Answer();
                            $keyboard = new Keyboard();

                            $bot->sendMessage(
                                $chatID,
                                $answer->getAnswer($message, CommandHelper::SUBSCRIBE),
                                'html',
                                false,
                                null,
                                $keyboard->getKeyboard($message, CommandHelper::SUBSCRIBE)
                            );
                            return false;
                        }
                    }
                });
            }

            $bot->run();

        } catch (Exception $e) {
            Logger::log('Exception', null, $e->getMessage());
        }
    }
}