<?php

require 'vendor/autoload.php';

use pcs\bot\services\BotClient;

$bot = new BotClient();
$bot->process();