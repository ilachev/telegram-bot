<?php


 $config['odbc_file']="/etc/odbc.ini";

if(file_exists($config['odbc_file'])){

	$ini_array = parse_ini_file($config['odbc_file'], true);
	if(sizeof($ini_array)>0){

		if (array_key_exists('acdr', $ini_array)) {

			$config['sql_host'] = $ini_array['acdr']['Server'];
			$config['sql_port'] = $ini_array['acdr']['Port'];
			$config['sql_db'] = $ini_array['acdr']['Database'];
			$config['sql_user'] = $ini_array['acdr']['User'];
			$config['sql_password'] = $ini_array['acdr']['Password'];

		}else{
		
		echo "Section acdr not found in ".$config['odbc_file']." file!";
		var_dump($ini_array);
	
		}
	
	}else{
	
	echo "File ".$config['odbc_file']." possible is empty!";	
	exit;
	}
}else{
	echo "Configuration file odbc.ini not found on /etc directory!";
	exit;
}


define('API_KEY', '<removed>');

define('DB_DSN', 'mysql:host='.$config['sql_host'].';dbname='.$config['sql_db'].';');
define('DB_USR', $config['sql_user']);
define('DB_PWD', $config['sql_password']);

define('SIPS_PATH', './sip.conf');


$admins = [
'30893259', //m.konchevich
'177952832', //dshleg
'612025923' //Galenko
];

