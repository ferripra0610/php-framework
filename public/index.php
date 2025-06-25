<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Routes/web.php';

use App\Core\Router;
use App\Core\Container;

\App\Core\Database::connect();
$container = new Container();

// Binding interfaces to implementations directly here for now.
// Later, these bindings should be moved into a separate class like AppServiceProvider
// to keep this file clean and make the application more modular and scalable.
$container->bind(App\Repositories\UserRepositoryInterface::class, App\Repositories\UserRepository::class);

$request = new App\Core\Request();
$response = new App\Core\Response();

Router::dispatch($request, $response, $container);
