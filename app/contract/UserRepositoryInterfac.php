<?php 
namespace App\Contract;

use App\Models\User;
interface UserRepositoryInterfac{
    public function getAllUsers() ;
    public function getUserById();
    public function updateUser(User  $user);
    public function editeUser(User  $user);
    public function createUser(array  $attributes);
    public function deleteUser(User $user,array $attributes);
} 