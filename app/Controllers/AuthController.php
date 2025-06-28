<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\JwtHelper;
use App\Services\UserService;

class AuthController
{

    public function __construct(private UserService $userService) {}

    public function login($request, $response)
    {
        $body = $request->body();
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        $user = $this->userService->findByEmail($username);
        if (!empty($user) && password_verify($password, $user->password)) {
            $token = JwtHelper::generateToken(['username' => $user->email]);
            return $response->json(['token' => $token]);
        }

        return $response->json(['message' => 'Invalid credentials'], 401);
    }
}
