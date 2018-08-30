<?php

namespace pcs\config;

class MainConfig
{
    public function init()
    {
        return [
            'sql' => [
                'host' => 'localhost',
                'port' => '3306',
                'db_name' => 'admin_efsoltel',
                'user' => 'admin_efsoltel',
                'password' => 'xJbTh0Sq0G'
            ],
            'api' => [
                'key' => '651882769:AAHPoFaQTkbPUtTMTArHc4ZgyxpmJvTJtrM'
            ],
            'admins' => [
                '30893259', //m.konchevich
                '177952832', //dshleg
                '612025923', //Galenko
                '505904694', //Galenko
            ]
        ];
    }
}