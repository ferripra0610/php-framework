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
                'driver'    => 'pgsql',
                'host'      => 'localhost',
                'database'  => 'excercise',
                'username'  => 'postgres',
                'password'  => 'root',
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
