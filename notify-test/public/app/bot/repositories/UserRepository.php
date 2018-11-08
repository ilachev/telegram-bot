<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Chat;
use Pcs\Bot\Models\Extension;
use Pcs\Bot\Models\User;

class UserRepository
{
    public function getUserPhoneByPhone($phone)
    {
        $user = User::all()->where('phone', '=', $phone)->first();

        if (!empty($user)) {
            $extension = Extension::all()->where('user_id', '=', $user->id)->first();
            return [
                'user' => $user,
                'extension' => $extension,
            ];
        }
        return null;
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

    public function getUserByPhone($phone)
    {
        return User::where('phone', '=', $phone)->first();
    }
}