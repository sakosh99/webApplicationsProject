<?php

namespace App\RepositoryInterface;

interface GroupRepositoryInterface
{
    public function all();
    public function create($attributes);
    public function find($group_id);
}
