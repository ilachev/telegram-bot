<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Extension;

class ExtensionRepository
{
    public function getExtensionByExtension($extension)
    {
        return Extension::all()->where('extension', '=', $extension)->first();
    }

    public function deleteExtension($extension)
    {
        $delExtension = Extension::all()->where('extension', '=', $extension)->first();

        if ($delExtension->delete()) {
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