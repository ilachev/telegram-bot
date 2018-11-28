<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Logger;
use Pcs\Bot\Models\AutoResponderStatus;
use Pcs\Bot\Models\Chat;
use Pcs\Bot\Models\Extension;
use Pcs\Bot\Models\Redirect;
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
            $status = AutoResponderStatus::where('user_id', '=', $delExtension->user_id)->first();
            $redirects = Redirect::all()->where('user_id', '=', $delExtension->user_id)->all();

            $user = User::where('id', '=', $delExtension->user_id)->first();

            $delExtension->delete();
            if ($chat) {
                $chat->delete();
            }
            if ($status) {
                $status->delete();
            }
            if ($redirects) {
                foreach ($redirects as $redirect) {
                    $redirect->delete();
                }
            }

            if ($user->delete()) {
                return true;
            }
            return null;
        }
        return null;
    }

    public function getExtensions()
    {
        return Extension::all()->toArray();
    }
}