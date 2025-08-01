<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;
use App\Services\UserService;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    private $userService;
    private $controller;

    protected function setUp(): void
    {
        $this->userService = $this->createMock(UserService::class);
        $this->controller = new AuthController($this->userService);
    }

    public function testLoginWithValidCredentials()
    {
        $email = 'user@example.com';
        $password = 'secret123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $user = new User();
        $user->email = $email;
        $user->password = $hashedPassword;

        // Anonymous class untuk Request
        $request = new class($email, $password) extends \App\Core\Request {
            private $email;
            private $password;

            public function __construct($email, $password)
            {
                $this->email = $email;
                $this->password = $password;
            }

            public function body()
            {
                return ['username' => $this->email, 'password' => $this->password];
            }
        };

        // Custom Response untuk menangkap output JSON
        $response = new class extends \App\Core\Response {
            public $output;
            public $status;

            public function json($data, $status = 200)
            {
                $this->output = $data;
                $this->status = $status;
                return $data;
            }
        };

        $this->userService->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $result = $this->controller->login($request, $response);

        $this->assertIsArray($response->output);
        $this->assertArrayHasKey('token', $response->output);
        $this->assertEquals(200, $response->status);
    }

    public function testLoginWithInvalidCredentials()
    {
        $email = 'user@example.com';
        $password = 'wrongpassword';

        $user = new User();
        $user->email = $email;
        $user->password = password_hash('correctpassword', PASSWORD_BCRYPT);

        $request = new class($email, $password) extends \App\Core\Request {
            private $email;
            private $password;

            public function __construct($email, $password)
            {
                $this->email = $email;
                $this->password = $password;
            }

            public function body()
            {
                return ['username' => $this->email, 'password' => $this->password];
            }
        };

        $response = new class extends \App\Core\Response {
            public $output;
            public $status;

            public function json($data, $status = 200)
            {
                $this->output = $data;
                $this->status = $status;
                return $data;
            }
        };

        $this->userService->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $this->controller->login($request, $response);

        $this->assertIsArray($response->output);
        $this->assertEquals(['message' => 'Invalid credentials'], $response->output);
        $this->assertEquals(401, $response->status);
    }
}
