<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\User;

class UserRepository
{
    public function getUserByPhone($phone)
    {
        $user = User::all()->where('phone', '=', $phone)->first();

        return $user;
    }
}