<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;
use Pcs\Bot\services\answer\admin\AdminAddingDirectionsAnswer;
use Pcs\Bot\services\answer\admin\AdminManageRedirectsAnswer;
use Pcs\Bot\services\answer\admin\AdminStartAnswer;
use Pcs\Bot\services\answer\NotAdminAnswer;
use Pcs\Bot\services\answer\user\AddingRedirectAnotherNumberAnswer;
use Pcs\Bot\services\answer\user\AddingRedirectAnswer;
use Pcs\Bot\services\answer\user\CreateRedirectNumberAnswer;
use Pcs\Bot\services\answer\user\ManageRedirectsAnswer;
use Pcs\Bot\services\answer\user\StartAnswer;
use Pcs\Bot\services\answer\user\SubscribeAnswer;
use Pcs\Bot\services\answer\user\ViewAllowedDirectionsAnswer;
use TelegramBot\Api\Types\Message;

class Answer
{
    private $userRepository;
    private $chatRepository;
    private $sessionRepository;
    private $mappingRepository;
    private $redirectRepository;
    private $adminList;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->chatRepository = new ChatRepository();
        $this->sessionRepository = new SessionRepository();
        $this->mappingRepository = new MappingRepository();
        $this->redirectRepository = new RedirectRepository();
        $this->adminList = $GLOBALS['admins'];
    }

    public function getAnswer(Message $message, $command = null)
    {
        if ($command == null) {
            $command = $message->getText();
        }

        $chatID = $message->getChat()->getId();
        $username = $message->getFrom()->getUsername();
        $currentStatus = $this->sessionRepository->getStatus($chatID);

        switch ($command) {
            case CommandHelper::START:
                if (in_array($chatID, $this->adminList)) {
                    return AdminStartAnswer::get($chatID, $username);
                } else {
                    return StartAnswer::get($chatID, $username);
                }

            case CommandHelper::SUBSCRIBE:
                return SubscribeAnswer::get($message);

            case CommandHelper::USER_MANAGEMENT:
                return 'Выберите пункт';

            case CommandHelper::MANAGE_REDIRECTS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminManageRedirectsAnswer::get($chatID);
                } else {
                    return ManageRedirectsAnswer::get($chatID);
                }

            case CommandHelper::ADDING_MAPPING:
                return 'Введите добавочный номер нового сотрудника';

            case CommandHelper::DELETING_MAPPING:
                return 'Введите добавочный номер';

            case CommandHelper::EDITING_MAPPING:
                return 'Введите добавочный номер';

            case CommandHelper::ADDING_DIRECTIONS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminAddingDirectionsAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::DELETING_DIRECTIONS:
                return 'Введите код страны и кол-во символов';

            case CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS:
                return ViewAllowedDirectionsAnswer::get($chatID);

            case CommandHelper::ADDING_REDIRECT:
                return AddingRedirectAnswer::get($chatID);

            case CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER:
                return AddingRedirectAnotherNumberAnswer::get($chatID);

            case CommandHelper::NO:
                return ManageRedirectsAnswer::get($chatID);

            case CommandHelper::YES:
                return CreateRedirectNumberAnswer::get($chatID, null, $type = 'yes');

            case CommandHelper::BACK:

                $answer = ' ';

                if ($currentStatus == SessionStatusHelper::MANAGE_REDIRECTS) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADDING_EXTENSION_REDIRECT) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADMIN_MANAGE_REDIRECTS) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::NOT_ADMIN) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTIONS) {
                    $answer = 'Выберите пункт';
                }
                return $answer;

            default:

                if ($currentStatus == SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER) {
                    return CreateRedirectNumberAnswer::get($chatID, $message->getText());
                }

                return 'Команда не существует';
        }

        return 'Команда не существует';
    }
}