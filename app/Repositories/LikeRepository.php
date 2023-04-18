<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Models\Like;
use App\Repositories\Contracts\ILikeRepository;

class LikeRepository extends BaseRepository implements ILikeRepository
{
    public function __construct(Like $like)
    {
        parent::__construct($like);
    }

    public function findLikeByBlogIdAndUserId(int $blogId, string $userId): BaseEntity|null
    {
        return $this->_model->where([
            ["blog_id", "=", $blogId],
            ["user_id", "=", $userId]
        ])->get()->first();
    }
}
