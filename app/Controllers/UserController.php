<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;

class UserController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index($request, $response)
    {
        $users = $this->userService->getAllUsers();
        return $response->json($users);
    }

    public function show($request, $response, $id)
    {
        return $response->json(['message' => 'User detail', 'user_id' => $id]);
    }

    public function store($request, $response)
    {
        $data = $request->body();
        return $response->json(['message' => 'User created', 'data' => $data], 201);
    }

    public function update($request, $response, $id)
    {
        $data = $request->body();
        return $response->json(['message' => 'User updated', 'user_id' => $id, 'data' => $data]);
    }

    public function delete($request, $response, $id)
    {
        return $response->json(['message' => 'User deleted', 'user_id' => $id]);
    }
}
