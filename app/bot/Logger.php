<?php

namespace Pcs\Bot;

use Monolog\Logger as Log;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Logger
{
    public static function log($data = null, $error = null)
    {
        // Create some handlers
        $stream = new StreamHandler(__DIR__. '/../../logs/app.log', Log::DEBUG);
        $firephp = new FirePHPHandler();

        // Create the main logger of the app
        $logger = new Log('app');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        if ($data) {
            $logger->info($data);
        }

        if ($error) {
            $logger->error( $error);
        }
    }
}