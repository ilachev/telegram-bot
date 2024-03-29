<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\MappingRepository;
use Pcs\Bot\repositories\RedirectRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\repositories\UserRepository;
use Pcs\Bot\services\answer\admin\AdminAddingDirectionsAnswer;
use Pcs\Bot\services\answer\admin\AdminAddingMappingAnswer;
use Pcs\Bot\services\answer\admin\AdminCreateAddingDirectionAnswer;
use Pcs\Bot\services\answer\admin\AdminCreateAddingMappingAnswer;
use Pcs\Bot\services\answer\admin\AdminCreateDeleteDirectionAnswer;
use Pcs\Bot\services\answer\admin\AdminCreateDeleteMappingAnswer;
use Pcs\Bot\services\answer\admin\AdminCreateEditingMappingAnswer;
use Pcs\Bot\services\answer\admin\AdminDeleteDirectionAnswer;
use Pcs\Bot\services\answer\admin\AdminDeleteMappingAnswer;
use Pcs\Bot\services\answer\admin\AdminEditingMappingAnswer;
use Pcs\Bot\services\answer\admin\AdminManageRedirectsAnswer;
use Pcs\Bot\services\answer\admin\AdminStartAnswer;
use Pcs\Bot\services\answer\admin\AdminUserManagementAnswer;
use Pcs\Bot\services\answer\admin\AdminViewMappingAnswer;
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
                if (in_array($chatID, $this->adminList)) {
                    return AdminUserManagementAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::MANAGE_REDIRECTS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminManageRedirectsAnswer::get($chatID);
                } else {
                    return ManageRedirectsAnswer::get($chatID);
                }

            case CommandHelper::VIEW_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return AdminViewMappingAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::ADDING_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return AdminAddingMappingAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::DELETING_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return AdminDeleteMappingAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::EDITING_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return AdminEditingMappingAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::ADDING_DIRECTIONS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminAddingDirectionsAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::DELETING_DIRECTIONS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminDeleteDirectionAnswer::get($chatID);
                } else {
                    return NotAdminAnswer::get($chatID);
                }

            case CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS:
                return ViewAllowedDirectionsAnswer::get($chatID);

            case CommandHelper::ADDING_REDIRECT:
                return AddingRedirectAnswer::get($chatID);

            case CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER:
                return AddingRedirectAnotherNumberAnswer::get($chatID);

            case CommandHelper::NO:
                if ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminDeleteDirectionAnswer::get($chatID, 'notSession');
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminDeleteMappingAnswer::get($chatID, 'notSession');
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_ALREADY_HAVE) {
                    if (in_array($chatID, $this->adminList)) {
                        return 'Выберите пункт';
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                }  elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_NOT_HAVE) {
                    if (in_array($chatID, $this->adminList)) {
                        return 'Выберите пункт';
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                }  elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return 'Выберите пункт';
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                } else {
                    return ManageRedirectsAnswer::get($chatID);
                }

            case CommandHelper::YES:
                if ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminCreateDeleteDirectionAnswer::get($chatID, $message->getText(), 'second');
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminCreateDeleteMappingAnswer::get($chatID, $message->getText(), 'second');
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_ALREADY_HAVE) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminDeleteMappingAnswer::get($chatID);
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                }  elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_NOT_HAVE) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminAddingMappingAnswer::get($chatID);
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                }  elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return AdminCreateEditingMappingAnswer::get($chatID, null, 'second');
                    } else {
                        return NotAdminAnswer::get($chatID);
                    }
                } else {
                    return CreateRedirectNumberAnswer::get($chatID, null, $type = 'yes');
                }

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
                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTION_FIRST_STEP) {
                    $answer = AdminAddingDirectionsAnswer::get($chatID, 'notSession');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTION_SECOND_STEP) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS_SECOND_STEP) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::USER_MANAGEMENT) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::VIEW_MAPPING) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_FIRST_STEP) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_SECOND_STEP) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_FIRST_STEP) {
                    $answer = AdminAddingMappingAnswer::get($chatID, 'notSession');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_SECOND_STEP) {
                    $answer = AdminCreateAddingMappingAnswer::get($chatID, null,'first');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_THIRD_STEP) {
                    $answer = 'Выберите пункт';
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_SECOND_STEP) {
                    $answer = AdminEditingMappingAnswer::get($chatID, 'notSession');
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_THIRD_STEP) {
                    $answer = AdminCreateEditingMappingAnswer::get($chatID, 'back','second');
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FOURTH_STEP) {
                    $answer = 'Выберите пункт';
                }
                return $answer;

            default:

                if ($currentStatus == SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER) {
                    return CreateRedirectNumberAnswer::get($chatID, $message->getText());
                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTIONS) {
                    return AdminCreateAddingDirectionAnswer::get($chatID, $message->getText(), 'first');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTION_FIRST_STEP) {
                    return AdminCreateAddingDirectionAnswer::get($chatID, $message->getText(), 'second');
                } elseif ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS) {
                    return AdminCreateDeleteDirectionAnswer::get($chatID, $message->getText(), 'first');
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING) {
                    return AdminCreateDeleteMappingAnswer::get($chatID, $message->getText(), 'first');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING) {
                    return AdminCreateAddingMappingAnswer::get($chatID, $message->getText(), 'first');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_FIRST_STEP) {
                    return AdminCreateAddingMappingAnswer::get($chatID, $message->getText(), 'second');
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_SECOND_STEP) {
                    return AdminCreateAddingMappingAnswer::get($chatID, $message->getText(), 'third');
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING) {
                    return AdminCreateEditingMappingAnswer::get($chatID, $message->getText(), 'first');
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_SECOND_STEP) {
                    return AdminCreateEditingMappingAnswer::get($chatID, $message->getText(), 'third');
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_THIRD_STEP) {
                    return AdminCreateEditingMappingAnswer::get($chatID, $message->getText(), 'fourth');
                }

                return 'Команда не существует';
        }

        return 'Команда не существует';
    }
}