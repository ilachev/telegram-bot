<?php

require 'vendor/autoload.php';
require 'config.php';

use Pcs\Bot\services\BotClient;

$bot = new BotClient();
$bot->process();