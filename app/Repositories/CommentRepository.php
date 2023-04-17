<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Models\Comment;
use App\Repositories\Contracts\ICommentRepository;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    public function createComment(CommentStoreRequest $request): BaseEntity
    {
        $comment = $this->_model->fill($request->all());

        $this->setAuditableInformationFromRequest($comment, $request);

        $comment->save();

        return $comment->fresh();
    }

    public function updateComment(CommentUpdateRequest $request): BaseEntity|null
    {
        $comment = $this->_model->find($request->id);

        if (!$comment) {
            return null;
        }

        $this->setAuditableInformationFromRequest($comment, $request);

        $comment->fill([
            'body' => $request->body
        ]);

        $comment->save();

        return $comment;
    }

    public function deleteComment(int $id): BaseEntity|null
    {
        $comment = $this->_model->find($id);

        if (!$comment) {
            return null;
        }

        $comment->delete();

        return $comment;
    }
}
