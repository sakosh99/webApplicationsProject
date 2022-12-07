<?php

namespace App\RepositoryInterface;

interface GroupRepositoryInterface
{
    public function all();

    public function create($attributes);
}
