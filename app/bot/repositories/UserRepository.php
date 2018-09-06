<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Chat;
use Pcs\Bot\Models\Extension;
use Pcs\Bot\Models\User;

class UserRepository
{
    public function getUserByPhone($phone)
    {
        $user = User::all()->where('phone', '=', $phone)->first();

        return $user;
    }

    public function getUserByChatID($chatID)
    {
        $chat = Chat::with('user')->where('chat_id', '=', $chatID)->first();

        return $chat->user;
    }

    public function getUsers()
    {
        return User::all()->toArray();
    }

    public function getMappings()
    {
        return Extension::with('user')->get();
    }

    public function getMappingByExtension($extension)
    {
        return Extension::with('user')->where('extension', '=', $extension)->first();
    }
}