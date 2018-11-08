<?php

defined('DB_DRIVER') or define('DB_DRIVER', 'mysql');
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_NAME') or define('DB_NAME', 'telegrambot');
defined('DB_USER') or define('DB_USER', 'telegrambot');
defined('DB_PASS') or define('DB_PASS', 'FhgkIyyTe50');

defined('CORP_DB_DRIVER') or define('CORP_DB_DRIVER', 'mysql');
defined('CORP_DB_HOST') or define('CORP_DB_HOST', 'localhost');
defined('CORP_DB_NAME') or define('CORP_DB_NAME', 'db_client_corp');
defined('CORP_DB_USER') or define('CORP_DB_USER', 'telegrambot');
defined('CORP_DB_PASS') or define('CORP_DB_PASS', 'FhgkIyyTe50');

defined('API_KEY') or define('API_KEY', '609369656:AAFbfjj1XD6oelKgl8ppYVxOdBUNMOsRED8');

defined('AMI_HOST') or define('AMI_HOST', '127.0.0.1');
defined('AMI_PORT') or define('AMI_PORT', '5038');
defined('AMI_USER') or define('AMI_USER', 'tlgrbot_user');
defined('AMI_PASS') or define('AMI_PASS', 'hJFkEEYY3Np');

/**
 * настройка сокета в формате socks5://110:ef2tlgrproxy@5.189.129.242:31080
 */
defined('PROXY_STRING') or define('PROXY_STRING', 'socks5://110:ef2tlgrproxy@5.189.129.242:31080');
#defined('PROXY_STRING') or define('PROXY_STRING', '');

$GLOBALS['admins'] = [
    '30893259', //m.konchevich
    '177952832', //dshleg
    '612025923', //Galenko
//    '505904694'
];