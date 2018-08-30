<?php

namespace Pcs\Bot\repositories\user;

use Pcs\Bot\Models\Users;

class UserRepository
{
    public function createUser($uid, $name, $phone)
    {
        $user = new Users();

        $user->uid = $uid;
        $user->name = $name;
        $user->phone = $phone;

        $user->save();
    }
}