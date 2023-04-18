<?php

namespace App\Services\Contracts;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;

interface ICommentService
{
    public function storeComment(int $blogId, CommentStoreRequest $request): GenericObjectResponse;

    public function updateComment(int $blogId, int $id, CommentUpdateRequest $request): GenericObjectResponse;

    public function destroyComment(int $blogId, int $id): BasicResponse;
}
