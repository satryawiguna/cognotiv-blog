<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IBlogRepository
{
    public function allBlogs(ListDataRequest $request): Collection;

    public function allSearchBlogs(ListSearchDataRequest $request): Collection;

    public function allSearchPageBlogs(ListSearchPageDataRequest $request): LengthAwarePaginator;

    public function findBlogById(int $id): BaseEntity|null;

    public function createBlog(BlogStoreRequest $request): BaseEntity;

    public function updateBlog(BlogUpdateRequest $request): BaseEntity|null;

    public function deleteBlog(int $id): BaseEntity|null;

    public function likeBlog(int $blogId, string $userId): BaseEntity;

    public function dislikeBlog(int $blogId, string $userId): BaseEntity|null;
}
