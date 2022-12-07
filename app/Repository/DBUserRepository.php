<?php

namespace App\Repository;

use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\RepositoryInterface;
use App\RepositoryInterface\UserRepositoryInterface;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;

class DBUserRepository implements UserRepositoryInterface
{
    use ModelHelper;

    public function all()
    {
        return User::all();
    }

    public function create($attributes)
    {
        return User::create($attributes);
    }

    public function update($user_id, $attributes)
    {
        $user = $this->findByIdOrFail(User::class, 'User', $user_id);
        return $user->update($attributes);
    }

    public function groupUsers($group_id)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', $group_id);
        return $group->users;
    }

    public function authenticatedUserProfile()
    {
        return Auth::user();
    }
}
