<?php

header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors','on');
ini_set('error_log', __DIR__ . '/logs/php.log');

require 'vendor/autoload.php';
require 'config.php';

use Pcs\Bot\services\BotHandler;
use Pcs\Bot\Models\Database;

new Database();
$bot = new BotHandler();
$bot->on();