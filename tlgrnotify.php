<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '/var/www/voip.efsol.ru/asterisk/notify/init.php';

if (count($argv) == 3 && is_numeric($argv[1]) && is_numeric($argv[2]))
{
	$subs	 = $numbers = $db->query('select * from subs where number=' . $argv[1])->fetchAll(2);
	foreach ($subs as $u)
	{
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array(["text" => "/отписаться"])), false, true);

		$bot->sendMessage($u['uid'], '<b>[' . $u['number'] . ']</b> Пропущенный вызов c номера ' . $argv[2] . '', 'html', false, null, $keyboard);

	}
}