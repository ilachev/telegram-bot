<?php

defined('DB_DRIVER') or define('DB_DRIVER', 'mysql');
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_NAME') or define('DB_NAME', 'admin_eftldbdev');
defined('DB_USER') or define('DB_USER', 'admin_eftldbdev');
defined('DB_PASS') or define('DB_PASS', 'xJbTh0Sq0G');

defined('API_KEY') or define('API_KEY', '651882769:AAHPoFaQTkbPUtTMTArHc4ZgyxpmJvTJtrM');

/**
 * настройка сокета в формате socks5://110:ef2tlgrproxy@5.189.129.242:31080
 */
defined('PROXY_STRING') or define('PROXY_STRING', '');

$GLOBALS['admins'] = [
    '30893259', //m.konchevich
    '177952832', //dshleg
    '505904694', //dshleg
    '612025923' //Galenko
];