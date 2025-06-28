<?php 

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function findAll();
    public function find($id);
    public function findByEmail($email);
    public function paginate($search = "", $page = 1, $size = 10, $sortBy = '', $sortDir = '');
}
