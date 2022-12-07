<?php

namespace App\Repository;

use App\Models\Group;
use App\Models\User;
use App\RepositoryInterface\GroupRepositoryInterface;

class GroupService
{
    public function __construct(private GroupRepositoryInterface $groupRepository)
    {
    }
    public function all()
    {
        return $this->groupRepository->all();
    }

    public function create($attributes)
    {
        return $this->groupRepository->create($attributes);
    }
}
