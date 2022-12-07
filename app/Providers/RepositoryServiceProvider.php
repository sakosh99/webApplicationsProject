<?php

namespace App\Providers;

use App\Repository\DBFileReportsRepository;
use App\Repository\DBFileRepository;
use App\Repository\DBGroupRepository;
use App\Repository\DBHealthRepository;
use App\Repository\DBUserRepository;
use App\RepositoryInterface\FileReportRepositoryInterface;
use App\RepositoryInterface\FileRepositoryInterface;
use App\RepositoryInterface\GroupRepositoryInterface;
use App\RepositoryInterface\HealthRepositoryInterface;
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
        $this->app->bind(FileRepositoryInterface::class, DBFileRepository::class);
        $this->app->bind(FileReportRepositoryInterface::class, DBFileReportsRepository::class);
        $this->app->bind(HealthRepositoryInterface::class, DBHealthRepository::class);
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
