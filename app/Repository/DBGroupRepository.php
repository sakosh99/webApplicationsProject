<?php

namespace App\Repository;

use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\FileRepositoryInterface;
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
}
