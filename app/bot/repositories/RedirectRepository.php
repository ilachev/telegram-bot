<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Redirect;

class RedirectRepository
{
    public function getRedirectForUser($userID)
    {
        $redirect = Redirect::all()->where('user_id', '=', $userID)->first();

        return $redirect->redirect;
    }

    public function updateRedirect($userID, $newRedirect)
    {
        $redirect = Redirect::all()->where('user_id', '=', $userID)->first();

        if ($redirect->redirect == $newRedirect) {
            return false;
        }
        $redirect->redirect = $newRedirect;
        $redirect->save();
        return true;
    }
}