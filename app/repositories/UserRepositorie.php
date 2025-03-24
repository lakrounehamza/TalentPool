<?php 

namespace App\Repositories;

use App\Contract\UserRepositoryInterfac;

use App\Models\User;

class  UserRepositorie implements UserRepositoryInterfac{
    
    public function getAllUsers(){} 
    public function getUserById(){}
    public function updateUser(User  $user){}
    public function editeUser(User  $user){}
    public function createUser(array  $attributes){}
    public function deleteUser(User $user,array $attributes){}
    public function getUserByRole($role){}
}