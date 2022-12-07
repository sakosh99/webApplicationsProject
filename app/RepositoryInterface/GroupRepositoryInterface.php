<?php

namespace App\RepositoryInterface;

use App\Models\Group;
use App\Models\User;

interface GroupRepositoryInterface
{
    public function all();
    public function create($attributes);
    public function find($group_id);
    public function delete(Group $group);
    public function userGroups($filter);
    public function groupsByUserId($user_id);
    public function attachUser(User $user, Group $group);
    public function detachUser(User $user, Group $group);
}
