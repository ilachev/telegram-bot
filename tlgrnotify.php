<?php

require_once 'notify/public/tlgrbot.php';

use Pcs\Bot\repositories\UserRepository;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\Logger;

/**
 * @var \TelegramBot\Api\BotApi $bot
 */

if (count($argv) == 3 && is_numeric($argv[1]) && is_numeric($argv[2])) {
    $userRepository = new UserRepository();
    $chatRepository = new ChatRepository();

    $userExtension = $userRepository->getMappingByExtension($argv[1]);
    $chatID = $chatRepository->getChatIDByUserID($userExtension->user->id);

    $answer = "<b>['. $userExtension->user->extension->extension .']</b> Пропущенный вызов c номера ' . $argv[2] . '";

    try {
        $bot->sendMessage(
            $chatID,
            $answer,
            'html',
            false,
            null,
            null
        );
    } catch (\TelegramBot\Api\InvalidArgumentException $e) {
        Logger::log('InvArg', $e->getMessage());
    } catch (\TelegramBot\Api\Exception $e) {
        Logger::log('Exception', $e->getMessage());
    }
}
