#!/usr/bin/env php
<?php

require '/var/www/voip.efsol.ru/asterisk/notify/public/vendor/autoload.php';
require '/var/www/voip.efsol.ru/asterisk/notify/public/config.php';


use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\Models\CorpClient;
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

    $CalleeNumber = '';
    $CallerNumber = '';
    $voiceFile = '';
    $answer = '';

    $userRepository = new UserRepository();
    $chatRepository = new ChatRepository();
    $statusRepository = new AutoResponderStatusRepository();

    if (!empty($files)) {
        foreach ($files as $file) {
            if (pathinfo($file)['extension'] == 'txt') {

                $fileContent = file(AUTO_RESPONDER_FILES_DIRECTORY . $file);
                foreach ($fileContent as $content) {

                    if (strpos($content, 'exten') !== false) {
                        $arContent = explode('=', $content);
                        $CalleeNumber = trim($arContent[1]);
                    }

                    if (strpos($content, 'callerid') !== false) {
                        $arContent = explode('=', $content);
                        preg_match('/<(\d+)>/i',$arContent[1],$CallerMatches);
                        $CallerNumber = trim($CallerMatches[1]);

                        if (strlen($CallerNumber) <= 4) {
                            $mapping = $userRepository->getMappingByExtension($CallerNumber);
                            $fio = $mapping->user->full_name;

                            if ($fio) {
                                $answer = 'Вам поступил звонок с номера ' . $CallerNumber . ' от ' . $fio . ' и было оставлено голосовое сообщение';
                            } else {
                                $answer = 'Вам поступил звонок с номера ' . $CallerNumber . ' и было оставлено голосовое сообщение';
                            }
                        } elseif (strlen($CallerNumber) > 4) {

                            $client = false;

                            if (strlen($argv[2]) >= 10) {
                                $client = CorpClient::where('client_number', 'like', '%' . substr($CallerNumber, -10))->first();
                            }

                            if ($client) {
                                $clientName = $client->client_name;

                                if (strlen($clientName) > 0) {
                                    $answer = 'Вам поступил звонок с номера ' . $CallerNumber . ' от контрагента ' . $clientName . ' и было оставлено голосовое сообщение';
                                } else {
                                    $answer = 'Вам поступил звонок с номера ' . $CallerNumber . ' и было оставлено голосовое сообщение. Контрагент не определён';
                                }
                            } else {
                                $answer = 'Вам поступил звонок с номера ' . $CallerNumber . ' и было оставлено голосовое сообщение. Контрагент не определён';
                            }
                        }
                    }
                }

            } elseif (pathinfo($file)['extension'] == 'wav') {
                $voiceFile = AUTO_RESPONDER_FILES_DIRECTORY . $file;
            }

            #unlink(AUTO_RESPONDER_FILES_DIRECTORY . $file);
        }
    }

    if (!empty($CalleeNumber)) {
        $userExtension = $userRepository->getMappingByExtension($CalleeNumber);
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
