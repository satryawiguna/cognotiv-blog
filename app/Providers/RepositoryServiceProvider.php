<?php

namespace App\Providers;

use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogRepository;
use App\Repositories\CommentRepository;
use App\Repositories\Contracts\IBlogCategoryRepository;
use App\Repositories\Contracts\IBlogRepository;
use App\Repositories\Contracts\ICommentRepository;
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
        $this->app->bind(IBlogRepository::class, BlogRepository::class);
        $this->app->bind(ICommentRepository::class, CommentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
