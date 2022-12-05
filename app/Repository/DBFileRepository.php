<?php

namespace App\Repository;

use App\Models\User;
use App\RepositoryInterface\FileRepositoryInterface;

class DBFileRepository implements FileRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create($attributes)
    {
        return User::create($attributes);
    }
}
