<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\Logger;
use TelegramBot\Api\Client;

class BotHandler
{
    private $commandsList = [
        CommandHelper::START,
        CommandHelper::UNSUBSCRIBE,
        CommandHelper::ADMIN,
        CommandHelper::LIST,
        CommandHelper::ULIST,
        CommandHelper::USET,
        CommandHelper::UNSET,
    ];

    private $allowedRawCommands = [
        CommandHelper::USER_MANAGEMENT,
        CommandHelper::ADDING_MAPPING,
        CommandHelper::DELETING_MAPPING,
        CommandHelper::EDITING_MAPPING,
        CommandHelper::VIEW_MAPPING,
        CommandHelper::MANAGE_REDIRECTS,
        CommandHelper::ADDING_DIRECTIONS,
        CommandHelper::DELETING_DIRECTIONS,
    ];

    private $bot;
    private $admins;

    public function __construct(Client $bot, $admins)
    {
        $this->bot = $bot;
        $this->admins = $admins;
    }

    public function execCommand()
    {
        $bot = $this->bot;
        $admins = $this->admins;
        $allowedRawCommands = $this->allowedRawCommands;

        foreach ($this->commandsList as $command) {

            $bot->command($command, function ($message) use ($bot, $admins, $command) {

                $answer = new Answer();
                $keyboard = new Keyboard();

                $chatID = $message->getChat()->getId();
                Logger::log($chatID);

                $bot->sendMessage(
                    $chatID,
                    $answer->getAnswer($command, $message, $admins),
                    'html',
                    false,
                    null,
                    $keyboard->getKeyboard($command));
            });
        }

        $bot->on(function($update) use ($bot, $admins, $allowedRawCommands) {

            $answer = new Answer();
            $keyboard = new Keyboard();

            $message = $update->getMessage();
            $chatID = $message->getChat()->getId();
            $messageText = $message->getText();

            if (array_search($messageText, $allowedRawCommands) !== false) {
                $bot->sendMessage(
                    $chatID,
                    $answer->getAnswer($messageText, $message, $admins),
                    'html',
                    false,
                    null,
                    $keyboard->getKeyboard($messageText)
                );
            }

        }, function() {
            return true;
        });

        $bot->run();
    }
}