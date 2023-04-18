<?php

namespace App\Http\Controllers\Api;


use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\BlogCategory\BlogCategoryStoreRequest;
use App\Http\Requests\BlogCategory\BlogCategoryUpdateRequest;
use App\Http\Resources\Blog\BlogCategoryResource;
use App\Http\Resources\Blog\BlogCategoryResourceCollection;
use App\Services\Contracts\IBlogCategoryService;

class BlogCategoryController extends ApiBaseController
{
    public IBlogCategoryService $_blogCategoryService;

    public function __construct(IBlogCategoryService $blogCategoryService)
    {
        $this->_blogCategoryService = $blogCategoryService;
    }

    public function all(ListDataRequest $request)
    {
        $blogCategories = $this->_blogCategoryService->getAllBlogCategories($request);

        if ($blogCategories->isError()) {
            return $this->getErrorLatestJsonResponse($blogCategories);
        }

        return $this->getListJsonResponse($blogCategories, BlogCategoryResourceCollection::class);
    }

    public function allSearch(ListSearchDataRequest $request)
    {
        $blogCategories = $this->_blogCategoryService->getAllSearchBlogCategories($request);

        if ($blogCategories->isError()) {
            return $this->getErrorLatestJsonResponse($blogCategories);
        }

        return $this->getListSearchJsonResponse($blogCategories, BlogCategoryResourceCollection::class);
    }

    public function allSearchPage(ListSearchPageDataRequest $request)
    {
        $blogCategories = $this->_blogCategoryService->getAllSearchPageBlogCategories($request);

        if ($blogCategories->isError()) {
            return $this->getErrorLatestJsonResponse($blogCategories);
        }

        return $this->getListSearchPageJsonResponse($blogCategories, BlogCategoryResourceCollection::class);
    }

    public function show(int $id)
    {
        $blogCategory = $this->_blogCategoryService->getBlogCategory($id);

        if ($blogCategory->isError()) {
            return $this->getErrorLatestJsonResponse($blogCategory);
        }

        return $this->getObjectJsonResponse($blogCategory, BlogCategoryResource::class);
    }

    public function store(BlogCategoryStoreRequest $request)
    {
        $storeBlogCategoryResponse = $this->_blogCategoryService->storeBlogCategory($request);

        if ($storeBlogCategoryResponse->isError()) {
            return $this->getErrorLatestJsonResponse($storeBlogCategoryResponse);
        }

        return $this->getObjectJsonResponse($storeBlogCategoryResponse, BlogCategoryResource::class);
    }

    public function update(int $id, BlogCategoryUpdateRequest $request)
    {
        $updateBlogCategoryResponse = $this->_blogCategoryService->updateBlogCategory($id, $request);

        if ($updateBlogCategoryResponse->isError()) {
            return $this->getErrorLatestJsonResponse($updateBlogCategoryResponse);
        }

        return $this->getObjectJsonResponse($updateBlogCategoryResponse, BlogCategoryResource::class);
    }

    public function delete(int $id)
    {
        $deleteBlogCategoryResponse = $this->_blogCategoryService->destroyBlogCategory($id);

        if ($deleteBlogCategoryResponse->isError()) {
            return $this->getErrorLatestJsonResponse($deleteBlogCategoryResponse);
        }

        return $this->getSuccessLatestJsonResponse($deleteBlogCategoryResponse);
    }
}
