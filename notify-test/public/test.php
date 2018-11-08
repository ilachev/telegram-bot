<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 29.10.18
 * Time: 10:54
 */

use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\VoicemailUsersListAction;

require_once 'vendor/autoload.php';
require_once 'config.php';

//$client = new ClientImpl([
//    'host' => AMI_HOST,
//    'scheme' => 'tcp://',
//    'port' => AMI_PORT,
//    'username' => AMI_USER,
//    'secret' => AMI_PASS,
//    'connect_timeout' => 10,
//    'read_timeout' => 10
//]);
//
//$client->open();
////
//$message = new \PAMI\Message\Action\DBGetAction('mobiles', '101');
////$message = new \PAMI\Message\Action\DBPutAction('mobiles', '123', '89160962525');
//
//$response = $client->send($message);
//
//var_dump($response->getKey('response'));
//var_dump($response);

//$eventsResponse = $response->getEvents();

//foreach ($eventsResponse as $eventResponse) {
//    var_dump($eventResponse->getRawContent());
//}

$database = new \Pcs\Bot\Models\Database();

$argv[2] = 2;

$client = false;

if (strlen($argv[2]) >= 10) {
    $client = \Pcs\Bot\Models\CorpClient::where('client_number', 'like', '%' . substr($argv[2], -10))->first();
}

//$test = \Pcs\Bot\Models\CorpClient::where('client_number', 'like', '%' . substr('2', -10))->first();

var_dump($client->client_name);
