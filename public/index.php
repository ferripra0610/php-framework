<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

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
