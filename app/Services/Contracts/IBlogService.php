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
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;

interface IBlogService
{
    public function getAllBlogCategories(ListDataRequest $request): GenericListResponse;

    public function getAllSearchBlogCategories(ListSearchDataRequest $request): GenericListSearchResponse;

    public function getAllSearchPageBlogCategories(ListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getBlogCategory(int $id): GenericObjectResponse;

    public function storeBlogCategory(BlogCategoryStoreRequest $request): GenericObjectResponse;

    public function updateBlogCategory(int $id, BlogCategoryUpdateRequest $request): GenericObjectResponse;

    public function destroyBlogCategory(string $id): BasicResponse;


    public function getAllBlogs(ListDataRequest $request): GenericListResponse;

    public function getAllSearchBlogs(ListSearchDataRequest $request): GenericListSearchResponse;

    public function getAllSearchPageBlogs(ListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getBlog(int $id): GenericObjectResponse;

    public function storeBlog(BlogStoreRequest $request): GenericObjectResponse;

    public function updateBlog(int $id, BlogUpdateRequest $request): GenericObjectResponse;

    public function destroyBlog(string $id): BasicResponse;
}
