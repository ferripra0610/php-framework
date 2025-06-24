<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Routes/web.php';

use App\Core\Router;
use App\Core\Container;

\App\Core\Database::connect();
$container = new Container();

$container->bind(App\Repositories\UserRepositoryInterface::class, App\Repositories\UserRepository::class);

$request = new App\Core\Request();
$response = new App\Core\Response();

Router::dispatch($request, $response, $container);
