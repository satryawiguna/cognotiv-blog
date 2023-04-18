<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends BaseEntity
{
    use HasFactory;

    protected $table = 'comments';

    protected $guarded = ['request_by'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
}
