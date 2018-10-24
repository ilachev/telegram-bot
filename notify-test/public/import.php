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

$path = realpath(__DIR__ . '/subs.csv');

$handle = fopen($path, 'r');

while ($data = fgetcsv($handle, 1000, ',')) {
    $mapping = $userRepository->getMappingByExtension($data[1]);
    $chatRepository->saveChatID($data[0], $mapping->user->id);
}