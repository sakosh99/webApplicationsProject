<?php

namespace App\RepositoryInterface;

interface UserRepositoryInterface
{
    public function all();
    public function create($attributes);
    public function groupUsers($group_id);
}
