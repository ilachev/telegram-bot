<?php

namespace Pcs\Bot\services\keyboard\user;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\UserRepository;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class AddingRedirectKeyboard
{
    public static function get($chatID)
    {
        $redirectRepository = new RedirectRepository();
        $userRepository = new UserRepository();
        $mappingRepository = new MappingRepository();

        $user = $userRepository->getUserByChatID($chatID);
        $redirect = $redirectRepository->getRedirectForUser($user->id);

        if (!empty($redirect)) {
            $keyboard = [
                [
                    ["text" => CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER],
                ],
                [
                    ["text" => CommandHelper::BACK]
                ],
                [
                    ["text" => CommandHelper::UNSUBSCRIBE]
                ]
            ];
        } else {
            $mappings = $mappingRepository->getMappings();

            $isAllowed = false;

            foreach ($mappings as $mapping) {
                $mappingLength = iconv_strlen($mapping['mapping']);
                $phoneLength = iconv_strlen($user->phone);

                $mappingKnownDigits = explode('*', $mapping['mapping']);

                if ((stripos($user->phone, $mappingKnownDigits[0]) !== false) && $mappingLength == $phoneLength) {
                    $isAllowed = true;
                    break;
                }
            }

            if ($isAllowed == false) {
                $keyboard = [
                    [
                        ["text" => CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER],
                    ],
                    [
                        ["text" => CommandHelper::BACK],
                    ],
                    [
                        ["text" => CommandHelper::UNSUBSCRIBE]
                    ]
                ];
            } else {
                $keyboard = [
                    [
                        ["text" => CommandHelper::YES],
                        ["text" => CommandHelper::NO],
                    ],
                    [
                        ["text" => CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER]
                    ],
                    [
                        ["text" => CommandHelper::UNSUBSCRIBE]
                    ]
                ];
            }
        }

        return new ReplyKeyboardMarkup(
            $keyboard,
            true,
            true
        );
    }
}