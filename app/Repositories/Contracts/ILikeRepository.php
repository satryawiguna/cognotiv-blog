<?php

namespace App\Repositories\Contracts;

use App\Core\Entities\BaseEntity;

interface ILikeRepository
{
    public function findLikeByBlogIdAndUserId(int $blogId, string $userId): BaseEntity|null;
}
