<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\AutoResponderStatus;
use Pcs\Bot\Models\Chat;

class AutoResponderStatusRepository
{
    public function getStatusForCurrentUser($chatId)
    {
        $userId = $this->getUserId($chatId);

        $status = AutoResponderStatus::where('user_id', '=', $userId)->first();

        if (!$status) {
            return $this->createStatus($userId);
        }
        return $status->status;
    }

    public function getUserId($chatId)
    {
        $chat = Chat::with('user')->where('chat_id', '=', $chatId)->first();

        if ($chat) {
            return $chat->user->id;
        }

        return null;
    }

    public function createStatus($userId)
    {
        $status = new AutoResponderStatus();
        $status->user_id = $userId;
        $status->status = 0;
        if ($status->save()) {
            return $status->status;
        }
        return null;
    }

    public function setStatus($chatId, $statusNumber)
    {
        $userId = $this->getUserId($chatId);

        $status = AutoResponderStatus::where('user_id', '=', $userId)->first();

        if ($status) {
            $status->status = $statusNumber;
            if ($status->update()) {
                return true;
            }
            return null;
        }
        return null;
    }
}