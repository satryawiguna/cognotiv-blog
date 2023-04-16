<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends BaseEntity
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_categories';

    protected $guarded = ['deleted_at'];

    protected $dates = ['deleted_at'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category', 'id');
    }
}
