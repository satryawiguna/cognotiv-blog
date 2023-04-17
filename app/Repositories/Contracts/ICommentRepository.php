<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;

interface ICommentRepository
{
    public function createComment(CommentStoreRequest $request): BaseEntity;

    public function updateComment(CommentUpdateRequest $request): BaseEntity|null;

    public function deleteComment(int $id): BaseEntity|null;
}
