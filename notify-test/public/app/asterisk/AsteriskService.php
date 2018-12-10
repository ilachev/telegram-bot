<?php

namespace Pcs\Asterisk;

use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\DBDelAction;
use PAMI\Message\Action\DBPutAction;

class AsteriskService
{
    private $client;

    public function __construct()
    {
        $this->client = new ClientImpl([
            'host' => AMI_HOST,
            'scheme' => 'tcp://',
            'port' => AMI_PORT,
            'username' => AMI_USER,
            'secret' => AMI_PASS,
            'connect_timeout' => 10,
            'read_timeout' => 10
        ]);

        $this->client->open();
    }

    public function updateRedirect($extension, $redirect)
    {
        $message = new DBPutAction('mobiles', $extension, $redirect);
        $response = $this->client->send($message);

        if ($response->getKey('response') == 'Success') {
            return true;
        }
        return null;
    }

    public function deleteRedirect($extension)
    {
        $message = new DBDelAction('mobiles', $extension);
        $response = $this->client->send($message);

        if ($response->getKey('response') == 'Success') {
            return true;
        }
        return null;
    }
}