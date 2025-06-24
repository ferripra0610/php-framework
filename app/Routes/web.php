<?php 

use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Middleware\JwtAuthMiddleware;

Router::post('/login', [AuthController::class, 'login']);

Router::get('/users', [UserController::class, 'index'], JwtAuthMiddleware::class);
Router::get('/users/{id}', [UserController::class, 'show'], JwtAuthMiddleware::class);
Router::post('/users', [UserController::class, 'store'], JwtAuthMiddleware::class);
Router::put('/users/{id}', [UserController::class, 'update'], JwtAuthMiddleware::class);
Router::delete('/users/{id}', [UserController::class, 'delete'], JwtAuthMiddleware::class);
