<?php

namespace Pcs\Bot\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public function __construct()
    {
        $capsule = new Capsule();

        $capsule->addConnection(
            [
                'driver' => DB_DRIVER,
                'host' => DB_HOST,
                'database' => DB_NAME,
                'username' => DB_USER,
                'password' => DB_PASS,
                'charset' => 'utf8',
                'collation' => 'utf8_general_ci',
                'prefix' => '',
            ],
            'default'
        );

        $capsule->addConnection(
            [
                'driver' => CORP_DB_DRIVER,
                'host' => CORP_DB_HOST,
                'database' => CORP_DB_NAME,
                'username' => CORP_DB_USER,
                'password' => CORP_DB_PASS,
                'charset' => 'utf8',
                'collation' => 'utf8_general_ci',
                'prefix' => '',
            ],
            'corpclients'
        );

        $capsule->bootEloquent();
    }
}