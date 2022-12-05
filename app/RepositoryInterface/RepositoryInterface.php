<?php

namespace App\RepositoryInterface;

interface RepositoryInterface
{
    public function all();

    public function create($attributes);
}
