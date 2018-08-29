<?php

require 'vendor/autoload.php';
require 'config.php';

$db = new PDO(DB_DSN, DB_USR, DB_PWD);

if(!array_key_exists('REQUEST_METHOD', $_SERVER) or $_SERVER['REQUEST_METHOD']=='POST') {

$bot = new \TelegramBot\Api\Client(API_KEY);
$bot->setProxy('socks5://110:ef2tlgrproxy@5.189.129.242:31080');

}

//
//function update_numbers()
//{
//	global $db;
//	$conf	 = parse_sips(SIPS_PATH);
//	$db->exec('truncate numbers');
//	$stmt	 = $db->prepare('insert into numbers(internal) values(:int)');
//	foreach ($conf as $section)
//	{
//		if (isset($section['username']))
//		{
//			$stmt->execute([':int' => $section['username']]);
//		}
//	}
//	$stmt->closeCursor();
//}
//
//function parse_sips($path)
//{
//	$file		 = file($path);
//	$conf		 = [];
//	$cur_section = [];
//
//	foreach ($file as $line)
//	{
//		$line = preg_replace('/\s/', '', $line);
//		if (mb_substr($line, 0, 1) != ';' && mb_strlen($line))
//		{
//			if (preg_match('/(\[[0-9]{3,3}\])/', $line, $matches))
//			{
//				if (isset($cur_name))
//				{
//					$conf[$cur_name] = $cur_section;
//				}
//				$cur_name	 = $matches[1];
//				$cur_section = [];
//			}
//			else
//			{
//				if (preg_match('/(\w+)\s*=\s*(.+)/', $line, $matches))
//				{
//					$cur_section[$matches[1]] = $matches[2];
//				}
//			}
//		}
//	}
//	if (isset($cur_name))
//		$conf[$cur_name] = $cur_section;
//
//	return $conf;
//}
