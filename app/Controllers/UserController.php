<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;

class UserController
{

    use Ext\Helper;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index($request, $response)
    {
        $this->setParameter($request);
        $users = $this->userService->paginate($this->search, $this->page, $this->size, $this->sortBy, $this->sortDir);
        return $response->json($users);
    }

    public function show($request, $response, $id)
    {
        $user = $this->userService->find($id);
        $response->json(['message' => 'Success', 'data' => $user], 200);
    }

    public function store($request, $response)
    {
        $body = $request->body();
        $body['password'] = password_hash($body['password'], PASSWORD_BCRYPT);
        $data = $this->userService->store($body);
        return $response->json(['message' => 'Success', 'data' => $data], 201);
    }

    public function update($request, $response, $id)
    {
        $data = $request->body();
        if (isset($data['password']))
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $res = $this->userService->update($id, $data);
        return $response->json(['message' => 'Success', 'user_id' => $id, 'data' => $data]);
    }

    public function delete($request, $response, $id)
    {
        $res = $this->userService->delete($id);
        return $response->json(['message' => 'Success', 'user_id' => $id]);
    }
}
