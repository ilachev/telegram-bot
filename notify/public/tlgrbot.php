<?php

require_once '../init.php';

error_reporting(E_ALL & ~(E_NOTICE | E_USER_NOTICE | E_DEPRECATED));
ini_set('display_errors', 1);

$config['logfile']='dump.txt';
function dump2log($logfile,$data) {

  $fh = fopen($logfile, 'a') or die('can\'t open file');
  if ((is_array($data)) || (is_object($data))) {

    $updateArray = print_r($data, TRUE);
    fwrite($fh, $updateArray.PHP_EOL);

  } else {

    fwrite($fh, $data.PHP_EOL);
  }

  fclose($fh);
}

if(array_key_exists('REQUEST_METHOD', $_SERVER)) {
	if($_SERVER['REQUEST_METHOD']=='POST') {

	try {

    $bot->command('start', function ($message) use ($bot,$config)
    {
        //dump2log($config['logfile'],$message->getChat()->getId());
                    $reply		 = "Добро пожаловать, {$message->getFrom()->getUsername()} ". PHP_EOL
                                   . "Данный бот предназначен для оповещения о пропущенных звонках по Вашему добавочному номеру." . PHP_EOL
                                   . "Для включения оповещений нажмите кнопку <b>Подписаться</b> и согласитесь с передачей Вашего мобильного номера боту." . PHP_EOL
                                   . "Вы можете отписаться от уведомлений нажав кнопку - <b>Отписаться</b>". PHP_EOL;
        $keyboard	 = new \TelegramBot\Api\Types\ReplyKeyboardMarkup([[["text" => "Подписаться", 'request_contact' => true]]], true, true);
        $bot->sendMessage($message->getChat()->getId(), $reply, 'html', false, null, $keyboard);
    });

	$bot->command('отписаться', function ($message) use ($bot, $db)
	{
		$user_id	 = $message->getChat()->getId();
		$db->exec('delete from subs where uid=' . $user_id);
		$db->exec('delete from users where uid=' . $user_id);
		$keyboard	 = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array(["text" => "Подписаться", 'request_contact' => true])), true, true);
		$bot->sendMessage($user_id, "Выполнено", null, false, null, $keyboard);
	});

	$bot->command('admin', function ($message) use ($bot, $admins)
	{

		if (in_array($message->getChat()->getId(), $admins))
		{
			$reply = "/list - просмотр списка подписок" . PHP_EOL
//					. "/uint - обновление номеров (удалит все сопоставления)" . PHP_EOL
					. "/ulist - просотр списка сопоставлений" . PHP_EOL
					. "/uset {internal} {mobile}- Задать сопоставление внутренний-номер" . PHP_EOL
					. "/unset {internal} {mobile} - Удалить сопоставление и подписку" . PHP_EOL
			;
		}
		else
		{
			$reply = 'Нет доступа';
		}

		$bot->sendMessage($message->getChat()->getId(), $reply);
	});

//	$bot->command('uint', function ($message) use ($bot, $admins, $db)
//	{
//		if (in_array($message->getChat()->getId(), $admins))
//		{
//			update_numbers();
//			$reply	 = "Список номеров обновлён" . PHP_EOL;
//			$numbers = $db->query('select * from numbers')->fetchAll(2);
//			foreach ($numbers as $number)
//			{
//				$reply .= "Внутренний: " . $number['internal'] .
//						' Номер пользователя: ' . $number['tg_number'] . PHP_EOL;
//			}
//			$bot->sendMessage($message->getChat()->getId(), $reply);
//		}
//	});
	$bot->command('list', function ($message) use ($bot, $admins, $db)
	{
		if (in_array($message->getChat()->getId(), $admins))
		{
			$reply	 = "Список подписок" . PHP_EOL;
			$numbers = $db->query('select subs.number, users.name,numbers.tg_number from subs '
							. 'join users on users.uid = subs.uid '
							. 'join numbers on subs.number=numbers.internal')->fetchAll(2);
			foreach ($numbers as $number)
			{
				$reply .= "Внутренний: " . $number['number'] . ' Пользователь: @' . $number['name'] . ' [' . $number['tg_number'] . ']' . PHP_EOL;
			}
			$bot->sendMessage($message->getChat()->getId(), $reply);
		}
	});

	$bot->command('ulist', function ($message) use ($bot, $admins, $db)
	{
		if (in_array($message->getChat()->getId(), $admins))
		{
			$reply	 = "Список сопоставлений" . PHP_EOL;
			$numbers = $db->query('select * from numbers')->fetchAll(2);
			foreach ($numbers as $number)
			{
				$reply .= "Внтурений: " . $number['internal'] . ' Мобильный: ' . $number['tg_number'] . PHP_EOL;
			}
			$bot->sendMessage($message->getChat()->getId(), $reply);
		}
	});
	$bot->command('uset', function ($message) use ($bot, $admins, $db)
	{
		if (in_array($message->getChat()->getId(), $admins))
		{
			$cmd_args	 = explode(' ', $message->getText());
			$reply		 = "Неверные параметры";
			if (count($cmd_args) == 3 && is_numeric($cmd_args[1]))
			{
				if ($db->exec('insert ignore into numbers(internal,tg_number) values(' . $cmd_args[1] . ',' . $db->quote($cmd_args[2]) . ');'))
				{
					$reply = 'Успешно';
				}
			}
			$bot->sendMessage($message->getChat()->getId(), $reply);
		}
	});
	$bot->command('unset', function ($message) use ($bot, $admins, $db)
	{
		if (in_array($message->getChat()->getId(), $admins))
		{
			$cmd_args	 = explode(' ', $message->getText());
			$reply		 = "Неверные параметры";
			if (count($cmd_args) == 3 && is_numeric($cmd_args[1]))
			{
				if ($db->exec('delete from numbers where internal=' . $cmd_args[1] . ' and tg_number=' . $db->quote($cmd_args[2])))
				{
					$db->exec('delete from subs where number=' . $cmd_args[1]);
					$reply = 'Успешно';
				}
			}

			$bot->sendMessage($message->getChat()->getId(), $reply);
		}
	});



	$bot->on(function($e) use ($bot, $db,$config)
	{
		$msg = $e->getMessage();

		$tg_number	 = str_replace('+', '', $msg->getContact()->getPhoneNumber());
		$user_id	 = $msg->getChat()->getId();
		$username	 = $msg->getFrom()->getUsername();



		$subscribe = $db->query('select subs.uid,subs.number from numbers '
							  . 'join subs on  numbers.internal=subs.number '
							  . 'where tg_number='. $db->quote($tg_number))->fetchAll(2);
		
		if(!$subscribe){
		
			$numbers = $db->query('select * from numbers where tg_number=' . $db->quote($tg_number))->fetchAll(2);
			$reply	 = 'Не найдено соответствие добавочного номера и вашего мобильного номера. ' . PHP_EOL
					 . 'Для решения проблемы - обратитесь на Хотлайн.';

		foreach ($numbers as $number) {

			$reply		 = "Вы успешно подписались на оповещения о пропущенных звонках на номер {$number['internal']}."
					. PHP_EOL . " Если это не ваш номер - обратитесь на Хотлайн";

			$db->exec('INSERT IGNORE  INTO users (uid,name) VALUES (' . $user_id . ',' . $db->quote($username) . ')');
			$db->exec('INSERT IGNORE  INTO subs (uid,number) VALUES (' . $user_id . ',' . $db->quote($number['internal']) . ')');
			$keyboard	 = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array(["text" => "/отписаться"])), false, true);
		}

		
		}else{

			$keyboard	 = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array(["text" => "/отписаться"])), false, true);
			$reply		 = "Добавочный номер <b>".$subscribe[0]['number']."</b> уже подписан на уведомления!";

		}

		$bot->sendMessage($user_id, $reply, 'html', false, null, $keyboard);


	}, function($e)
	{
		return $e->getMessage()->getContact()->getPhoneNumber() ? true : false;
	});



	$bot->run();
}
catch (\TelegramBot\Api\Exception $e)
{
	$e->getMessage();
}

}
}

?>