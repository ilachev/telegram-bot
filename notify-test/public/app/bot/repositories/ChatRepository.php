<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Chat;

class ChatRepository
{
    public function saveChatID($chatID, $userID)
    {
        $chat = Chat::all()->where('chat_id', '=', $chatID)->first();

        if (empty($chat->chat_id)) {
            $newChat = new Chat();
            $newChat->chat_id = $chatID;
            $newChat->user_id = $userID;
            $newChat->save();
        }
    }

    public function getChatByChatID($chatID)
    {
        return Chat::all()->where('chat_id', '=', $chatID)->first();
    }

    public function getUserIDByChatID($chatID)
    {
        $chat = Chat::all()->where('chat_id', '=', $chatID)->first();

        return $chat->user_id;
    }

    public function getChatIDByUserID($userID)
    {
        $chat = Chat::all()->where('user_id', '=', $userID)->first();

        return $chat->chat_id;
    }

    public function deleteChat($chatID)
    {
        $chat = $this->getChatByChatID($chatID);
        if ($chat) {
            $chat->delete();
            return true;
        }
        return null;
    }
}