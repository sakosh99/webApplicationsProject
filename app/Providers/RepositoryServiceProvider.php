<?php

namespace App\Providers;

use App\Repository\DBFileRepository;
use App\Repository\DBUserRepository;
use App\RepositoryInterface\FileRepositoryInterface;
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
