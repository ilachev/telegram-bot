<?php

namespace Pcs\Bot\repositories;

use Pcs\Bot\Models\Session;

class SessionRepository
{
    public function getSessionByID($id)
    {
        return Session::all()->where('session_id', '=', $id)->first();
    }

    public function setSession($id)
    {
        $session = new Session();
        $session->session_id = $id;
        $session->save();
    }

    public function setStatus($sessionID, $status)
    {
        $session = $this->getSessionByID($sessionID);

        if (empty($session->session_id)) {
            $this->setSession($sessionID);
        } else {
            $session->status = $status;
            $session->save();
        }
    }

    public function getStatus($sessionID)
    {
        $session = $this->getSessionByID($sessionID);

        return $session->status;
    }
}