<?php

namespace App\Repository;

use App\Models\Health;
use App\RepositoryInterface\HealthRepositoryInterface;

class DBHealthRepository implements HealthRepositoryInterface
{
    public function all()
    {
        $reports = Health::all();
        return $reports;
    }
    public function latest()
    {
        $lastBatch = Health::latest('created_at')->first();
        $reports = Health::whereBatch($lastBatch->batch)->get();
        return $reports;
    }
}
