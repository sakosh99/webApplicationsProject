<?php

namespace App\Providers;

use App\Repository\DBFileRepository;
use App\Repository\DBGroupRepository;
use App\Repository\DBUserRepository;
use App\RepositoryInterface\FileRepositoryInterface;
use App\RepositoryInterface\GroupRepositoryInterface;
use App\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, DBUserRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, DBGroupRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
