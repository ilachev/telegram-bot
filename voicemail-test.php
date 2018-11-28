<?php

require '/var/www/voip.efsol.ru/asterisk/notify-test/public/vendor/autoload.php';
require '/var/www/voip.efsol.ru/asterisk/notify-test/public/config.php';


use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\AutoResponderStatusRepository;
use Pcs\Bot\repositories\UserRepository;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\services\BotHandler;
use Pcs\Bot\Models\Database;

/**
 * @var \TelegramBot\Api\BotApi $bot
 */
new Database();

if (count($argv) == 3) {

    $filename = $argv[2];

    $files = array_filter(scandir(AUTO_RESPONDER_FILES_DIRECTORY), function ($file) use ($filename) {
        return pathinfo(AUTO_RESPONDER_FILES_DIRECTORY . $file)['filename'] == $filename;
    });

    $extension = '';
    $voiceFile = '';
    $answer = '';
    if (!empty($files)) {
        foreach ($files as $file) {
            if (pathinfo($file)['extension'] == 'txt') {
                $fileContent = file(AUTO_RESPONDER_FILES_DIRECTORY . $file);
                foreach ($fileContent as $content) {
                    if (strpos($content, 'exten') !== false) {
                        $arContent = explode('=', $content);
                        $extension = trim($arContent[1]);
                        $answer = 'Вам поступил звонок с добавочного ' . $extension . ' и было оставлено голосовое сообщение';
                    }
                }
            } elseif (pathinfo($file)['extension'] == 'wav') {
                $voiceFile = AUTO_RESPONDER_FILES_DIRECTORY . $file;
            }

            unlink(AUTO_RESPONDER_FILES_DIRECTORY . $file);
        }
    }

    if (!empty($extension)) {
        $userRepository = new UserRepository();
        $chatRepository = new ChatRepository();
        $statusRepository = new AutoResponderStatusRepository();

        $userExtension = $userRepository->getMappingByExtension($extension);
        $userId = $userExtension->user->id;

        if (!empty($userExtension)) {
            $chatID = $chatRepository->getChatIDByUserID($userId);
            $autoResponderStatus = $statusRepository->getStatusByUserId($userId);

            if ($autoResponderStatus == CommandHelper::AUTO_RESPONDER_ON_NUMBER) {
                $bot = new BotHandler();
                $bot->on(false, $chatID, $answer, $argv[1]);
            }
        }
    }

} else {
    print "Please set all arguments...";
}
