<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Services\Contracts\ICommentService;
use Illuminate\Http\Request;

class CommentController extends ApiBaseController
{
    public ICommentService $_commentService;

    public function __construct(ICommentService $commentService)
    {
        $this->_commentService = $commentService;
    }

    public function store(CommentStoreRequest $request)
    {
        $storeCommentResponse = $this->_commentService->storeComment($request);

        if ($storeCommentResponse->isError()) {
            return $this->getErrorLatestJsonResponse($storeCommentResponse);
        }

        return $this->getObjectJsonResponse($storeCommentResponse, CommentResource::class);
    }

    public function update(int $id, CommentUpdateRequest $request)
    {
        $updateCommentResponse = $this->_commentService->updateComment($id, $request);

        if ($updateCommentResponse->isError()) {
            return $this->getErrorLatestJsonResponse($updateCommentResponse);
        }

        return $this->getObjectJsonResponse($updateCommentResponse, CommentResource::class);
    }

    public function delete(int $id)
    {
        $deleteCommentResponse = $this->_commentService->destroyComment($id);

        if ($deleteCommentResponse->isError()) {
            return $this->getErrorLatestJsonResponse($deleteCommentResponse);
        }

        return $this->getSuccessLatestJsonResponse($deleteCommentResponse);
    }
}
