<?php

require 'notify/public/vendor/autoload.php';
require 'notify/public/config.php';

use Pcs\Bot\repositories\UserRepository;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\services\BotHandler;
use Pcs\Bot\Models\Database;
use Pcs\Bot\Logger;

/**
 * @var \TelegramBot\Api\BotApi $bot
 */

new Database();

if (count($argv) == 3 && is_numeric($argv[1]) && is_numeric($argv[2])) {
    $userRepository = new UserRepository();
    $chatRepository = new ChatRepository();

    $userExtension = $userRepository->getMappingByExtension($argv[1]);
    if (!empty($userExtension)) {
        $chatID = $chatRepository->getChatIDByUserID($userExtension->user->id);
        $extension = $userExtension->user->extension->extension;

        if (strlen($argv[2]) <= 4) {
            $mapping = $userRepository->getMappingByExtension($argv[2]);
            $fio = $mapping->user->full_name;

            if ($fio) {
                $answer = "У Вас пропущенный вызов c доб. $argv[2] от " . $fio;
            } else {
                $answer = "У Вас пропущенный вызов c доб. $argv[2]";
            }
        } elseif (strlen($argv[2]) > 4) {

            $client = false;

            if (strlen($argv[2]) >= 10) {
                $client = \Pcs\Bot\Models\CorpClient::where('client_number', 'like', '%' . substr($argv[2], -10))->first();
            }

            if ($client) {
                $clientName = $client->client_name;

                if (strlen($clientName) > 0) {
                    $answer = "У Вас пропущенный вызов c номера $argv[2] от контрагента '" . $clientName . "'";
                } else {
                    $answer = "У Вас пропущенный вызов c номера $argv[2]. Контрагент не определён";
                }
            } else {
                $answer = "У Вас пропущенный вызов c номера $argv[2]. Контрагент не определён";
            }
        }

        $bot = new BotHandler();
        $bot->on(true, $chatID, $answer);
    }
}
