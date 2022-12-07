<?php

namespace App\RepositoryInterface;

use App\Models\Group;
use App\Models\User;

interface UserRepositoryInterface
{
    public function all();
    public function create($attributes);
    public function update(User $user, $attributes);
    public function groupUsers(Group $group);
    public function authenticatedUserProfile();
    public function find($user_id);
}
