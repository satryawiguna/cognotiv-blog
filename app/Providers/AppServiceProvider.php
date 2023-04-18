<?php

namespace App\Providers;

use App\Core\Contracts\IService;
use App\Services\BaseService;
use App\Services\BlogCategoryService;
use App\Services\BlogService;
use App\Services\CommentService;
use App\Services\Contracts\IBlogCategoryService;
use App\Services\Contracts\IBlogService;
use App\Services\Contracts\ICommentService;
use App\Services\Contracts\IUserService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IBlogCategoryService::class, BlogCategoryService::class);
        $this->app->bind(IBlogService::class, BlogService::class);
        $this->app->bind(ICommentService::class, CommentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
