<?php

namespace App\Repository;

use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\FileRepositoryInterface;
use App\RepositoryInterface\GroupRepositoryInterface;

class DBGroupRepository implements GroupRepositoryInterface
{
    public function all()
    {
        return Group::all();
    }

    public function create($attributes)
    {
        return Group::create($attributes);
    }
}
