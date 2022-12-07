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

    public function update(User $user, $attributes)
    {
        return $user->update($attributes);
    }

    public function groupUsers(Group $group)
    {
        return $group->users;
    }

    public function authenticatedUserProfile()
    {
        return Auth::user();
    }

    public function find($user_id)
    {
        $user = $this->findByIdOrFail(User::class, 'User', $user_id);
        return $user;
    }
}
