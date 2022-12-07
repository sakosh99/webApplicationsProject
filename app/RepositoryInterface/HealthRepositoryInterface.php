<?php

namespace App\RepositoryInterface;

interface HealthRepositoryInterface
{
    public function all();
    public function latest();
}
