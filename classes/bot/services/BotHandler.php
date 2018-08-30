<?php

namespace pcs\bot\services;

use pcs\helpers\CommandHelper;
use PDO;
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
    private $db;
    private $admins;

    public function __construct(Client $bot, PDO $db, $admins)
    {
        $this->bot = $bot;
        $this->db = $db;
        $this->admins = $admins;
    }

    public function execCommand()
    {
        $bot = $this->bot;
        $db = $this->db;
        $admins = $this->admins;
        $allowedRawCommands = $this->allowedRawCommands;

        foreach ($this->commandsList as $command) {

            $bot->command($command, function ($message) use ($bot, $db, $admins, $command) {

                $answer = new Answer();
                $keyboard = new Keyboard();

                $chatID = $message->getChat()->getId();

                $bot->sendMessage(
                    $chatID,
                    $answer->getAnswer($db, $command, $message, $admins),
                    'html',
                    false,
                    null,
                    $keyboard->getKeyboard($command));
            });
        }

        $bot->on(function($update) use ($bot, $db, $admins, $allowedRawCommands) {

            $answer = new Answer();
            $keyboard = new Keyboard();

            $message = $update->getMessage();
            $chatID = $message->getChat()->getId();
            $messageText = $message->getText();

            if (array_search($messageText, $allowedRawCommands) !== false) {
                $bot->sendMessage(
                    $chatID,
                    $answer->getAnswer($db, $messageText, $message, $admins),
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