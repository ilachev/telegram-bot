<?php

namespace Pcs\Bot\services;

use TelegramBot\Api\Client;

class BotClient
{
    private $config;

    public function __construct()
    {
        $this->config['admins'] = [
            '30893259', //m.konchevich
            '177952832', //dshleg
            '612025923', //Galenko
            '505904694', //Galenko
        ];
    }

    public function process()
    {
        $command = new BotHandler(
            new Client(API_KEY),
            $this->config['admins']
        );
        $command->execCommand();
    }
}