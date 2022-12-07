<?php

namespace App\RepositoryInterface;

interface UserRepositoryInterface
{
    public function all();
    public function create($attributes);
    public function update($user_id, $attributes);
    public function groupUsers($group_id);
    public function authenticatedUserProfile();
}
