<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function connect()
    {
        try {
            $capsule = new Capsule;
            $capsule->addConnection([
                'driver'    => config('db.driver'),
                'host'      => config('db.host'),
                'database'  => config('db.database'),
                'username'  => config('db.username'),
                'password'  => config('db.password'),
                'charset'   => 'utf8',
                'prefix'    => '',
                'schema'    => 'public',
            ]);

            // Optional: global access
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
        } catch (\Throwable $th) {
            echo "connection failed ... !";
        }
    }
}
