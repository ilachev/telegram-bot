<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Extension;
use Pcs\Bot\Models\User;

class ExtensionRepository
{
    public function getExtensionByExtension($extension)
    {
        return Extension::all()->where('extension', '=', $extension)->first();
    }

    public function deleteExtension($extension)
    {
        $delExtension = Extension::all()->where('extension', '=', $extension)->first();
        $user = User::all()->where('id', '=', $delExtension->user_id)->first();

        if ($delExtension->delete()) {
            $user->delete();

            return true;
        } else {
            return null;
        }
    }

    public function getExtensions()
    {
        return Extension::all()->toArray();
    }
}