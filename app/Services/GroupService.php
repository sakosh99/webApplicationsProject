<?php

namespace App\Services;

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
