<?php

namespace App\Providers;

use App\Repositories\BlogCategoryRepository;
use App\Repositories\Contracts\IBlogCategoryRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IBlogCategoryRepository::class, BlogCategoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
