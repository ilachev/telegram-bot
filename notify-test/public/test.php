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

//$test = \Pcs\Bot\Models\CorpClient::where('client_number', '=', '791650280')->first();
//$usr = \Pcs\Bot\Models\User::where('id', '=', 1)->first();

$chat = \Pcs\Bot\Models\Chat::where('chat_id', '=', '505904694')->first();
$ext = \Pcs\Bot\Models\Extension::where('user_id', '=', $chat->user_id)->first();
var_dump($ext->extension);
