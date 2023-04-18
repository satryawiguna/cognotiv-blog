<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends BaseEntity
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $table = 'blogs';

    protected $guarded = ['deleted_at', 'request_by'];

    protected $dates = ['deleted_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'blog_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
