<?php

use PHPUnit\Framework\TestCase;
use App\Services\UserService;
use App\Repositories\UserRepositoryInterface;

class UserServiceTest extends TestCase
{
    private $userRepository;
    private $userService;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testGetAllUsers()
    {
        $expected = [['id' => 1, 'name' => 'Alice']];
        $this->userRepository->method('findAll')->willReturn($expected);

        $result = $this->userService->getAllUsers();

        $this->assertEquals($expected, $result);
    }

    public function testFindByEmail()
    {
        $email = 'user@example.com';
        $user = ['id' => 1, 'email' => $email];

        $this->userRepository->method('findByEmail')->with($email)->willReturn($user);

        $result = $this->userService->findByEmail($email);

        $this->assertEquals($user, $result);
    }

    public function testFindById()
    {
        $user = ['id' => 1, 'name' => 'Bob'];
        $this->userRepository->method('find')->with(1)->willReturn($user);

        $result = $this->userService->find(1);

        $this->assertEquals($user, $result);
    }

    public function testPaginate()
    {
        $data = [['id' => 1, 'name' => 'Alice']];
        $this->userRepository->method('paginate')
            ->with('search', 1, 10, 'id', 'asc')
            ->willReturn($data);

        $result = $this->userService->paginate('search', 1, 10, 'id', 'asc');

        $this->assertEquals($data, $result);
    }

    public function testStoreHashesPasswordAndStoresUser()
    {
        $input = ['name' => 'Alice', 'password' => 'plainpass'];
        $this->userRepository
            ->expects($this->once())
            ->method('store')
            ->with($this->callback(function($data) {
                return isset($data['password']) &&
                       $data['name'] === 'Alice' &&
                       password_verify('plainpass', $data['password']);
            }))
            ->willReturn(['id' => 1]);

        $result = $this->userService->store($input);

        $this->assertEquals(['id' => 1], $result);
    }

    public function testUpdateWithPassword()
    {
        $input = ['name' => 'Bob', 'password' => 'newpass'];
        $this->userRepository
            ->expects($this->once())
            ->method('update')
            ->with(2, $this->callback(function ($data) {
                return isset($data['password']) &&
                       password_verify('newpass', $data['password']);
            }))
            ->willReturn(true);

        $result = $this->userService->update(2, $input);

        $this->assertTrue($result);
    }

    public function testUpdateWithoutPassword()
    {
        $input = ['name' => 'Bob'];
        $this->userRepository
            ->expects($this->once())
            ->method('update')
            ->with(2, $this->callback(function ($data) {
                return !isset($data['password']) && $data['name'] === 'Bob';
            }))
            ->willReturn(true);

        $result = $this->userService->update(2, $input);

        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $this->userRepository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $result = $this->userService->delete(1);

        $this->assertTrue($result);
    }
}
