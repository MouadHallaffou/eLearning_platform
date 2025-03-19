<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $data);
    public function findUserByEmail($email);
    public function updateUser($id, array $data);
}