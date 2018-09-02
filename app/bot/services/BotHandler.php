<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\Logger;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class BotHandler
{
    private $commandsList = [
        CommandHelper::ADMIN,
        CommandHelper::LIST,
        CommandHelper::ULIST,
        CommandHelper::USET,
        CommandHelper::UNSET,
    ];

    private $allowedRawCommands = [
        CommandHelper::SUBSCRIBE,
        CommandHelper::UNSUBSCRIBE,
        CommandHelper::USER_MANAGEMENT,
        CommandHelper::ADDING_MAPPING,
        CommandHelper::DELETING_MAPPING,
        CommandHelper::EDITING_MAPPING,
        CommandHelper::VIEW_MAPPING,
        CommandHelper::MANAGE_REDIRECTS,
        CommandHelper::ADDING_DIRECTIONS,
        CommandHelper::DELETING_DIRECTIONS,
    ];

    public function on()
    {
        try {
            $bot = new Client(API_KEY);

            $admins = [
                '30893259', //m.konchevich
                '177952832', //dshleg
                '612025923', //Galenko
                '505904694', //Galenko
            ];

            $bot->command(CommandHelper::START, function ($message) use ($bot) {
                /**
                 * @var Message $message
                 * @var BotApi $bot
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


            $allowedRawCommands = $this->allowedRawCommands;

            $bot->on(function(Update $update) use ($bot, $allowedRawCommands) {
                /**
                 * @var Message $message
                 * @var BotApi $bot
                 */

                $answer = new Answer();
                $keyboard = new Keyboard();

                $message = $update->getMessage();
                $chatID = $message->getChat()->getId();

                if ((array_search($message->getText(), $allowedRawCommands) !== false)) {
                    $bot->sendMessage(
                        $chatID,
                        $answer->getAnswer($message),
                        'html',
                        false,
                        null,
                        $keyboard->getKeyboard($message)
                    );
                }

            }, function(Update $update) use ($bot) {
                /**
                 * @var BotApi $bot
                 */

                $message = $update->getMessage();
                $chatID = $message->getChat()->getId();

                if (!empty($message->getContact()->getPhoneNumber())) {

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

                return true;
            });

            $bot->run();

        } catch (Exception $e) {
            Logger::log('Exc', $e->getMessage());
        }

    }
}