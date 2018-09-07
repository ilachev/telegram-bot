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

    public function saveUserWithExtension($extension, $phone, $fullName)
    {
        $user = new User();
        $user->phone = $phone;
        $user->full_name = $fullName;
        $user->save();

        $newExtension = new Extension();
        $newExtension->extension = $extension;

        $user->extension()->save($newExtension);
    }

    public function editUserWithExtension($extension, $phone, $fullName)
    {
        $mapping = Extension::with('user')->where('extension', '=', $extension)->first();

        $mapping->user()->update([
            'phone' => $phone,
            'full_name' => $fullName,
        ]);
    }
}