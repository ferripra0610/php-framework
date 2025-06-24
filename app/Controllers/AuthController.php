<?php 

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\JwtHelper;

class AuthController
{
    public function login($request, $response)
    {
        $body = $request->body();
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        if ($username === 'admin' && $password === 'password') {
            $token = JwtHelper::generateToken(['username' => $username]);
            return $response->json(['token' => $token]);
        }

        return $response->json(['message' => 'Invalid credentials'], 401);
    }
}
