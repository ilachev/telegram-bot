<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Logger;
use Pcs\Bot\Models\Chat;
use Pcs\Bot\Models\Extension;
use Pcs\Bot\Models\User;

class ExtensionRepository
{
    public function getExtensionByExtension($extension)
    {
        return Extension::where('extension', '=', $extension)->first();
    }

    public function deleteExtension($extension)
    {
        $delExtension = Extension::where('extension', '=', $extension)->first();

        if ($delExtension) {

            $chat = Chat::where('user_id', '=', $delExtension->user_id)->first();
            $user = User::where('id', '=', $delExtension->user_id)->first();

            if ($delExtension->delete()) {
                if ($chat->delete()) {
                    $user->delete();
                    return true;
                }
                return false;
            } else {
                return null;
            }
        }
        return null;
    }

    public function getExtensions()
    {
        return Extension::all()->toArray();
    }
}