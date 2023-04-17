<?php

namespace App\Http\Controllers\Api;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Http\Resources\Blog\BlogResourceCollection;
use App\Services\Contracts\IBlogService;

class BlogController extends ApiBaseController
{
    public IBlogService $_blogService;

    public function __construct(IBlogService $blogService)
    {
        $this->_blogService = $blogService;
    }

    public function all(ListDataRequest $request)
    {
        $blogs = $this->_blogService->getAllBlogs($request);

        if ($blogs->isError()) {
            return $this->getErrorLatestJsonResponse($blogs);
        }

        return $this->getListJsonResponse($blogs, BlogResourceCollection::class);
    }

    public function allSearch(ListSearchDataRequest $request)
    {
        $blogs = $this->_blogService->getAllSearchBlogs($request);

        if ($blogs->isError()) {
            return $this->getErrorLatestJsonResponse($blogs);
        }

        return $this->getListSearchJsonResponse($blogs, BlogResourceCollection::class);
    }

    public function allSearchPage(ListSearchPageDataRequest $request)
    {
        $blogs = $this->_blogService->getAllSearchPageBlogs($request);

        if ($blogs->isError()) {
            return $this->getErrorLatestJsonResponse($blogs);
        }

        return $this->getListSearchPageJsonResponse($blogs, BlogResourceCollection::class);
    }

    public function show(int $id)
    {
        $blog = $this->_blogService->getBlog($id);

        if ($blog->isError()) {
            return $this->getErrorLatestJsonResponse($blog);
        }

        return $this->getObjectJsonResponse($blog, BlogResource::class);
    }

    public function store(BlogStoreRequest $request)
    {
        $storeBlogResponse = $this->_blogService->storeBlog($request);

        if ($storeBlogResponse->isError()) {
            return $this->getErrorLatestJsonResponse($storeBlogResponse);
        }

        return $this->getObjectJsonResponse($storeBlogResponse, BlogResource::class);
    }

    public function update(BlogUpdateRequest $request, int $id)
    {
        $updateBlogResponse = $this->_blogService->updateBlog($id, $request);

        if ($updateBlogResponse->isError()) {
            return $this->getErrorLatestJsonResponse($updateBlogResponse);
        }

        return $this->getObjectJsonResponse($updateBlogResponse, BlogResource::class);
    }

    public function delete(int $id)
    {
        $deleteBlogResponse = $this->_blogService->destroyBlog($id);

        if ($deleteBlogResponse->isError()) {
            return $this->getErrorLatestJsonResponse($deleteBlogResponse);
        }

        return $this->getSuccessLatestJsonResponse($deleteBlogResponse);
    }
}
