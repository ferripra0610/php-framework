<?php 

namespace App\Services;

use App\Repositories\UserRepositoryInterface;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function findByEmail($email){
        return $this->userRepository->findByEmail($email);
    }

    public function find($id){
        return $this->userRepository->find($id);
    }

    public function paginate($search = "", $page = 1, $size = 10, $sortBy = '', $sortDir = ''){
        return $this->userRepository->paginate($search, $page, $size, $sortBy, $sortDir);
    }

    public function store($body){
        $body['password'] = password_hash($body['password'], PASSWORD_BCRYPT);
        return $this->userRepository->store($body);
    }

    public function update($id, $body){
        if (isset($body['password']))
            $body['password'] = password_hash($body['password'], PASSWORD_BCRYPT);
        else
            unset($body['password']);

        return $this->userRepository->update($id, $body);
    }

    public function delete($id){
        return $this->userRepository->delete($id);
    }
}
