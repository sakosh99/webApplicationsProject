<?php

namespace App\Repository;

use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\GroupRepositoryInterface;
use App\Traits\ModelHelper;

class DBGroupRepository implements GroupRepositoryInterface
{
    use ModelHelper;
    public function all()
    {
        return Group::all();
    }

    public function create($attributes)
    {
        return Group::create($attributes);
    }

    public function find($group_id)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', $group_id);
        return $group;
    }

    public function delete(Group $group)
    {
        return $group->delete();
    }
    public function userGroups($filter)
    {
        $groups = Group::dynamicSearch($filter)->get();
        return $groups;
    }
    public function groupsByUserId($user_id)
    {
        $groups = Group::where('publisher_id', $user_id)->get();
        return $groups;
    }
    public function attachUser(User $user, Group $group)
    {
        $user->inGroups()->attach($group);
    }
    public function detachUser(User $user, Group $group)
    {
        $user->inGroups()->detach($group);
    }
}
