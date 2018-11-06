<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 07.09.18
 * Time: 15:36
 */

require 'tlgrbot.php';

$userRepository = new \Pcs\Bot\repositories\UserRepository();
$chatRepository = new \Pcs\Bot\repositories\ChatRepository();

$path = realpath(__DIR__ . '/fio.csv');

$handle = fopen($path, 'r');

//while ($data = fgetcsv($handle, 1000, ',')) {
//    $fio = trim($data[0]) . ' ' . trim($data[1]) . ' ' . trim($data[2]);
//    $numbers = $data[4];
//    $arNumbers = explode('+', $numbers);
//    foreach ($arNumbers as $number) {
//        if (empty(trim($number))) {
//            continue;
//        }
//        $number = str_replace([' ', '-'], '', $number);
//
//        $user = \Pcs\Bot\Models\User::where('phone', '=', $number)->first();
//        if ($user) {
//            $user->full_name = $fio;
//            $user->update();
//        }
//    }
//}

//$users = \Pcs\Bot\Models\User::all()->toArray();
$extension = \Pcs\Bot\Models\Extension::where('extension', '=', '110')->first();
$user = \Pcs\Bot\Models\User::where('id', '=', '151')->first();
var_dump($user->full_name);