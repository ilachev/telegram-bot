<?php

namespace pcs\bot;

use PDO;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class BotCommandConstructor
{
    private $commandsList = [
        'start',
        'отписаться',
        'admin',
        'list',
        'ulist',
        'uset',
        'unset',
    ];
    private $bot;
    private $db;

    public function __construct(Client $bot, PDO $db)
    {
        $this->bot = $bot;
        $this->db = $db;
    }

    public function execCommand()
    {
        $bot = $this->bot;
        $db = $this->db;

        foreach ($this->commandsList as $command) {
            $bot->command($command, function ($message) use ($bot, $db) {
                $reply = 'Тест';
                $keyboard = new ReplyKeyboardMarkup([[["text" => "Подписаться", 'request_contact' => true]]], true, true);
                $bot->sendMessage($message->getChat()->getId(), $reply, 'html', false, null, $keyboard);
            });
        }

        $bot->run();
    }
}