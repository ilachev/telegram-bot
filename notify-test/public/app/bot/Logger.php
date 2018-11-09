<?php

namespace Pcs\Bot;

use Monolog\Logger as Log;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Logger
{
    public static function log($message, $data = null, $error = null)
    {
        if (!file_exists(__DIR__ . '/../../logs/app.log')) {
            fopen(__DIR__ . '/../../logs/app.log', 'a');
        }

        if (is_writable(__DIR__ . '/../../logs/app.log')) {
            // Create some handlers
            $stream = new StreamHandler(__DIR__. '/../../logs/app.log', Log::DEBUG);
            $firephp = new FirePHPHandler();

            // Create the main logger of the app
            $logger = new Log('app');
            $logger->pushHandler($stream);
            $logger->pushHandler($firephp);

            $logger->info($message, [
                'info' => $data
            ]);

            if ($error) {
                $logger->error('Error', [
                    'error' => $error
                ]);
            }
        } else {
            return null;
        }
    }
}