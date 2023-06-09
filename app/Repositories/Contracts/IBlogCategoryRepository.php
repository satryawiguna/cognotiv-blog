<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\BlogCategory\BlogCategoryStoreRequest;
use App\Http\Requests\BlogCategory\BlogCategoryUpdateRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IBlogCategoryRepository
{
    public function allBlogCategories(ListDataRequest $request): Collection;

    public function allSearchBlogCategories(ListSearchDataRequest $request): Collection;

    public function allSearchPageBlogCategories(ListSearchPageDataRequest $request): LengthAwarePaginator;

    public function findBlogCategoryById(int $id): BaseEntity|null;

    public function createBlogCategory(BlogCategoryStoreRequest $request): BaseEntity;

    public function updateBlogCategory(BlogCategoryUpdateRequest $request): BaseEntity|null;

    public function deleteBlogCategory(int $id): BaseEntity|null;
}
