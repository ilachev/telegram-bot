<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\User;

class UserRepository
{
    public function getUserByPhone($phone)
    {
        $user = User::where('phone', '=', $phone)->toArray();
        return $user;
    }

    public function getExtension($user)
    {
        return User::find($user)->extension();
    }
}