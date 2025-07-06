<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use App\Services\UserService;

class UserControllerTest extends TestCase
{
    private $userService;
    private $controller;

    protected function setUp(): void
    {
        $this->userService = $this->createMock(UserService::class);
        $this->controller = new UserController($this->userService);
    }

    private function makeFakeRequest(array $body = [])
    {
        return new class($body) extends \App\Core\Request {
            private $bodyData;

            public function __construct(array $body)
            {
                $this->bodyData = $body;
            }

            public function body()
            {
                return $this->bodyData;
            }
        };
    }

    private function makeFakeResponse()
    {
        return new class extends \App\Core\Response {
            public $output;
            public $status;

            public function json($data, $status = 200)
            {
                $this->output = $data;
                $this->status = $status;
                return $data;
            }
        };
    }

    public function testIndexReturnsPaginatedUsers()
    {
        $users = [['id' => 1, 'name' => 'Alice']];
        $this->userService->method('paginate')->willReturn($users);

        // Buat dummy request yang akan diproses oleh trait Ext\Helper
        $request = new class([]) extends \App\Core\Request {
            public function get()
            {
                return [
                    'search' => 'Alice',
                    'page' => 1,
                    'size' => 10,
                    'sortBy' => 'id',
                    'sortDir' => 'asc',
                ];
            }
        };

        $response = $this->makeFakeResponse();

        $result = $this->controller->index($request, $response);

        $this->assertEquals($users, $response->output);
    }

    public function testShowReturnsUserById()
    {
        $user = ['id' => 1, 'name' => 'Alice'];
        $this->userService->method('find')->with(1)->willReturn($user);

        $request = $this->makeFakeRequest();
        $response = $this->makeFakeResponse();

        $this->controller->show($request, $response, 1);

        $this->assertEquals(['message' => 'Success', 'data' => $user], $response->output);
        $this->assertEquals(200, $response->status);
    }

    public function testStoreCreatesUser()
    {
        $data = ['name' => 'Alice'];
        $created = ['id' => 1, 'name' => 'Alice'];

        $this->userService->method('store')->with($data)->willReturn($created);

        $request = $this->makeFakeRequest($data);
        $response = $this->makeFakeResponse();

        $this->controller->store($request, $response);

        $this->assertEquals(['message' => 'Success', 'data' => $created], $response->output);
        $this->assertEquals(201, $response->status);
    }

    public function testUpdateModifiesUser()
    {
        $data = ['name' => 'Bob'];
        $this->userService->method('update')->with(1, $data)->willReturn(true);

        $request = $this->makeFakeRequest($data);
        $response = $this->makeFakeResponse();

        $this->controller->update($request, $response, 1);

        $this->assertEquals(['message' => 'Success Update', 'user_id' => 1], $response->output);
    }

    public function testDeleteRemovesUser()
    {
        $this->userService->method('delete')->with(1)->willReturn(true);

        $request = $this->makeFakeRequest();
        $response = $this->makeFakeResponse();

        $this->controller->delete($request, $response, 1);

        $this->assertEquals(['message' => 'Success', 'user_id' => 1], $response->output);
    }
}
