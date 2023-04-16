<?php

namespace App\Services\Contracts;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Requests\Blog\BlogCategoryStoreRequest;
use App\Http\Requests\Blog\BlogCategoryUpdateRequest;

interface IBlogService
{
    public function getAllBlogCategories(ListDataRequest $request): GenericListResponse;

    public function getAllSearchBlogCategories(ListSearchDataRequest $request): GenericListSearchResponse;

    public function getAllSearchPageBlogCategories(ListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getBlogCategory(int $id): GenericObjectResponse;

    public function storeBlogCategory(BlogCategoryStoreRequest $request): GenericObjectResponse;

    public function updateBlogCategory(int $id, BlogCategoryUpdateRequest $request): GenericObjectResponse;

    public function destroyBlogCategory(string $id): BasicResponse;
}
