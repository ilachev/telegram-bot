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

        $mapping = $userRepository->getMappingByExtension($argv[2]);
        $fio = $mapping->user->full_name;

        $answer = "У Вас пропущенный вызов c доб. $argv[2] от " . $fio;

        $bot = new BotHandler();
        $bot->on(true, $chatID, $answer);
    }
}
