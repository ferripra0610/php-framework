<?php 

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function findAll()
    {
        $users = User::all();
        return $users;
    }
}
