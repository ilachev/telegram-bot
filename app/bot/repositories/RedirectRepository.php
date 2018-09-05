<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Redirect;

class RedirectRepository
{
    public function getRedirectForUser($userID)
    {
        $redirect = Redirect::all()->where('user_id', '=', $userID)->first();

        return (!empty($redirect)) ? $redirect->redirect : '';
    }

    public function updateRedirect($userID, $newRedirect)
    {
        $redirect = Redirect::all()->where('user_id', '=', $userID)->first();

        if (empty($redirect)) {
            $redirect = new Redirect();
            $redirect->user_id = $userID;
        }

        if ($redirect->redirect == $newRedirect) {
            return false;
        }
        $redirect->redirect = $newRedirect;
        $redirect->save();
        return true;
    }

    public function setRedirect($userID, $redirect)
    {
        $newRedirect = new Redirect();
        $newRedirect->user_id = $userID;
        $newRedirect->redirect = $redirect;
        if ($newRedirect->save()) {
            return true;
        }
        return false;
    }
}