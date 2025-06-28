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

    public function find($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function paginate($search = '', $page = 1, $size = 10, $sortBy = '', $sortDir = '')
    {
        $users = User::select('id', 'name', 'email')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            });

        if (!empty($sortBy))
            $users->orderBy($sortBy, $sortDir);

        return $users->paginate($size, ['*'], 'page', $page);
    }

    public function findByEmail($email)
    {
        return User::where("email", $email)->first();
    }

    public function store($body){
        return User::create($body);
    }

    public function update($id, $body){
        return User::where('id', $id)->update($body);
    }
    
    public function delete($id){
        return User::destroy($id);
    }
}
