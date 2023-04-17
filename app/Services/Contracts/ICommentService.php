<?php

namespace App\Services\Contracts;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;

interface ICommentService
{
    public function storeComment(CommentStoreRequest $request): GenericObjectResponse;

    public function updateComment(int $id, CommentUpdateRequest $request): GenericObjectResponse;

    public function destroyComment(string $id): BasicResponse;
}
