<?php

header('Content-Type: text/html; charset=utf-8');

require 'vendor/autoload.php';
require 'config.php';

use Pcs\Bot\services\BotHandler;
use Pcs\Bot\Models\Database;

new Database();
$bot = new BotHandler();
$bot->on();