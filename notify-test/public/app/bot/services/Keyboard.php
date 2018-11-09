<?php

namespace Pcs\Bot\services;

use Pcs\Bot\helpers\CommandHelper;
use Pcs\Bot\helpers\SessionStatusHelper;
use Pcs\Bot\Logger;
use Pcs\Bot\repositories\ChatRepository;
use Pcs\Bot\repositories\SessionRepository;
use Pcs\Bot\services\keyboard\admin\AdminAddingDirectionsKeyboard;
use Pcs\Bot\services\keyboard\admin\AdminManageRedirectsKeyboard;
use Pcs\Bot\services\keyboard\admin\AdminStartKeyboard;
use Pcs\Bot\services\keyboard\admin\AdminUserManagementKeyboard;
use Pcs\Bot\services\keyboard\BackKeyboard;
use Pcs\Bot\services\keyboard\NotAdminKeyboard;
use Pcs\Bot\services\keyboard\user\AddingRedirectKeyboard;
use Pcs\Bot\services\keyboard\user\ManageRedirectsKeyboard;
use Pcs\Bot\services\keyboard\user\StartKeyboard;
use Pcs\Bot\services\keyboard\YesNoKeyboard;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Keyboard
{
    private $chatRepository;
    private $sessionRepository;
    private $adminList;

    public function __construct()
    {
        $this->chatRepository = new ChatRepository();
        $this->sessionRepository = new SessionRepository();
        $this->adminList = $GLOBALS['admins'];
    }

    public function getKeyboard(Message $message, $command = null)
    {
        if ($command == null) {
            $command = $message->getText();
        }

        $chatID = $message->getChat()->getId();
        $currentStatus = $this->sessionRepository->getStatus($chatID);

        switch ($command) {
            case CommandHelper::START:
                if (in_array($chatID, $this->adminList)) {
                    return AdminStartKeyboard::get($chatID);
                } else {
                    return StartKeyboard::get($chatID);
                }

            case CommandHelper::SUBSCRIBE:
                if (empty($this->sessionRepository->getStatus($chatID))) {
                    return null;
                }
                if (in_array($chatID, $this->adminList)) {
                    return AdminStartKeyboard::get($chatID);
                } else {
                    return StartKeyboard::get($chatID);
                }

            case CommandHelper::USER_MANAGEMENT:
                if (in_array($chatID, $this->adminList)) {
                    return AdminUserManagementKeyboard::get($chatID);
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::MANAGE_REDIRECTS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminManageRedirectsKeyboard::get($chatID);
                } else {
                    return ManageRedirectsKeyboard::get($chatID);
                }

            case CommandHelper::VIEW_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return BackKeyboard::get();
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::ADDING_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return BackKeyboard::get();
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::DELETING_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return BackKeyboard::get();
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::EDITING_MAPPING:
                if (in_array($chatID, $this->adminList)) {
                    return BackKeyboard::get();
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::ADDING_DIRECTIONS:
                if (in_array($chatID, $this->adminList)) {
                    return AdminAddingDirectionsKeyboard::get();
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::DELETING_DIRECTIONS:
                if (in_array($chatID, $this->adminList)) {
                    return BackKeyboard::get();
                } else {
                    return NotAdminKeyboard::get();
                }

            case CommandHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS:
                return BackKeyboard::get();

            case CommandHelper::ADDING_REDIRECT:
                return AddingRedirectKeyboard::get($chatID);

            case CommandHelper::ADDING_REDIRECT_ANOTHER_NUMBER:
                return BackKeyboard::get();

            case CommandHelper::NO:
                if ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS_FIRST_STEP) {
                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_DIRECTIONS);
                    return AdminUserManagementKeyboard::get($chatID);
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_FIRST_STEP) {
                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::DELETING_MAPPING);
                    return AdminUserManagementKeyboard::get($chatID);
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_ALREADY_HAVE) {
                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_NOT_HAVE) {
                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FIRST_STEP) {
                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);
                } else {
                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::MANAGE_REDIRECTS);
                    return ManageRedirectsKeyboard::get($chatID);
                }

            case CommandHelper::YES:
                if ($currentStatus == SessionStatusHelper::DELETING_MAPPING) {
                    if (in_array($chatID, $this->adminList)) {
                        return BackKeyboard::get();
                    } else {
                        return NotAdminKeyboard::get();
                    }
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_NOT_HAVE) {
                    if (in_array($chatID, $this->adminList)) {
                        return BackKeyboard::get();
                    } else {
                        return NotAdminKeyboard::get();
                    }
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FIRST_STEP) {
                    if (in_array($chatID, $this->adminList)) {
                        return BackKeyboard::get();
                    } else {
                        return NotAdminKeyboard::get();
                    }
                } else {
                    return BackKeyboard::get();
                }

            case CommandHelper::BACK:

                $keyboard = [];

                if ($currentStatus == SessionStatusHelper::MANAGE_REDIRECTS) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::START);

                    $keyboard = [
                        [
                            ["text" => CommandHelper::MANAGE_REDIRECTS]
                        ],
                        [
                            ["text" => CommandHelper::UNSUBSCRIBE]
                        ]
                    ];
                } elseif ($currentStatus == SessionStatusHelper::VIEW_ALLOWED_DIRECTIONS_REDIRECTS) {

                    if (in_array($chatID, $this->adminList)) {
                        $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_MANAGE_REDIRECTS);
                        return AdminManageRedirectsKeyboard::get($chatID);
                    } else {
                        $this->sessionRepository->setStatus($chatID, SessionStatusHelper::MANAGE_REDIRECTS);
                        return ManageRedirectsKeyboard::get($chatID);
                    }

                } elseif ($currentStatus == SessionStatusHelper::ADDING_EXTENSION_REDIRECT) {

                    if (in_array($chatID, $this->adminList)) {
                        $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_MANAGE_REDIRECTS);
                        return AdminManageRedirectsKeyboard::get($chatID);
                    } else {
                        $this->sessionRepository->setStatus($chatID, SessionStatusHelper::MANAGE_REDIRECTS);
                        return ManageRedirectsKeyboard::get($chatID);
                    }

                } elseif ($currentStatus == SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_EXTENSION_REDIRECT);
                    return AddingRedirectKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS) {

                    if (in_array($chatID, $this->adminList)) {
                        $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_MANAGE_REDIRECTS);
                        return AdminManageRedirectsKeyboard::get($chatID);
                    } else {
                        $this->sessionRepository->setStatus($chatID, SessionStatusHelper::MANAGE_REDIRECTS);
                        return ManageRedirectsKeyboard::get($chatID);
                    }

                } elseif ($currentStatus == SessionStatusHelper::ADMIN_MANAGE_REDIRECTS) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::NOT_ADMIN) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::START);
                    return StartKeyboard::get($chatID);
                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTIONS) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_MANAGE_REDIRECTS);
                    return AdminManageRedirectsKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTION_FIRST_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_DIRECTIONS);
                    return BackKeyboard::get();

                } elseif ($currentStatus == SessionStatusHelper::ADDING_DIRECTION_SECOND_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_MANAGE_REDIRECTS);
                    return AdminManageRedirectsKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS_SECOND_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::USER_MANAGEMENT) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::VIEW_MAPPING) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::USER_MANAGEMENT);
                    return AdminUserManagementKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::USER_MANAGEMENT);
                    return AdminUserManagementKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_FIRST_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::USER_MANAGEMENT);
                    return AdminUserManagementKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_SECOND_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::USER_MANAGEMENT);
                    return AdminUserManagementKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_FIRST_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING);
                    return BackKeyboard::get();

                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_SECOND_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADDING_MAPPING_FIRST_STEP);
                    return BackKeyboard::get();

                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_THIRD_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::USER_MANAGEMENT);
                    return AdminUserManagementKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FIRST_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::USER_MANAGEMENT);
                    return AdminUserManagementKeyboard::get($chatID);

                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_SECOND_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING);
                    return BackKeyboard::get();

                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_THIRD_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::EDITING_MAPPING_SECOND_STEP);
                    return BackKeyboard::get();

                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FOURTH_STEP) {

                    $this->sessionRepository->setStatus($chatID, SessionStatusHelper::ADMIN_START);
                    return AdminStartKeyboard::get($chatID);

                }

                return new ReplyKeyboardMarkup(
                    $keyboard,
                    true,
                    true
                );

            case CommandHelper::UNSUBSCRIBE;
                return StartKeyboard::get($chatID);

            default:

                if ($currentStatus == SessionStatusHelper::DELETING_DIRECTIONS_FIRST_STEP) {
                    return YesNoKeyboard::get();
                } elseif ($currentStatus == SessionStatusHelper::DELETING_MAPPING_FIRST_STEP) {
                    return YesNoKeyboard::get();
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_FIRST_STEP) {
                    return BackKeyboard::get();
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_SECOND_STEP) {
                    return BackKeyboard::get();
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_THIRD_STEP) {
                    return BackKeyboard::get();
                } elseif ($currentStatus == SessionStatusHelper::ADDING_MAPPING_ALREADY_HAVE) {
                    return YesNoKeyboard::get();
                } elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_NOT_HAVE) {
                    return YesNoKeyboard::get();
                }  elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_FIRST_STEP) {
                    return YesNoKeyboard::get();
                }  elseif ($currentStatus == SessionStatusHelper::EDITING_MAPPING_THIRD_STEP) {
                    return BackKeyboard::get();
                } else {
                    return null;
                }
        }
    }
}