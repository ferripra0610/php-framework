<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as DB;
use App\Models\User;
use App\Repositories\UserRepository;

class UserRepositoryTest extends TestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        $db = new DB();
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $db->setAsGlobal();
        $db->bootEloquent();

        // Buat tabel 'users' secara manual
        DB::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // Isi dummy user
        User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => password_hash('secret', PASSWORD_BCRYPT),
        ]);

        $this->repository = new UserRepository();
    }

    public function testFindAll()
    {
        $users = $this->repository->findAll();
        $this->assertCount(1, $users);
        $this->assertEquals('Alice', $users[0]->name);
    }

    public function testFind()
    {
        $user = $this->repository->find(1);
        $this->assertEquals('Alice', $user->name);
    }

    public function testFindByEmail()
    {
        $user = $this->repository->findByEmail('alice@example.com');
        $this->assertEquals(1, $user->id);
    }

    public function testStore()
    {
        $newUser = $this->repository->store([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
        ]);

        $this->assertEquals('Bob', $newUser->name);
        $this->assertDatabaseHas('users', ['email' => 'bob@example.com']);
    }

    public function testUpdate()
    {
        $this->repository->update(1, ['name' => 'Alice Updated']);
        $user = $this->repository->find(1);
        $this->assertEquals('Alice Updated', $user->name);
    }

    public function testDelete()
    {
        $this->repository->delete(1);
        $user = $this->repository->find(1);
        $this->assertNull($user);
    }

    public function testPaginate()
    {
        // Tambah user agar lebih dari satu
        User::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => password_hash('pass', PASSWORD_BCRYPT),
        ]);

        $result = $this->repository->paginate('', 1, 10, 'id', 'asc');
        $this->assertEquals(2, $result->total());
        $this->assertEquals(1, $result->currentPage());
    }

    // Helper manual untuk asert SQL jika diperlukan
    protected function assertDatabaseHas(string $table, array $conditions)
    {
        $found = DB::table($table)->where($conditions)->exists();
        $this->assertTrue($found, "Failed asserting that table [$table] has matching record.");
    }
}
