<?php

namespace pcs\bot;

use pcs\config\MainConfig;
use PDO;
use TelegramBot\Api\Client;

class BotClient
{
    private $config;

    public function __construct()
    {
        $this->config = (new MainConfig())->init();
    }

    public function process()
    {
        $command = new BotCommandConstructor(
            new Client($this->config['api']['key']),
            new PDO(
                'mysql:host=' . $this->config['sql']['host'] . ';dbname='. $this->config['sql']['db_name'] . ';',
                $this->config['sql']['user'],
                $this->config['sql']['password']
            )
        );
        $command->execCommand();
    }
}