<?php

namespace App\Models;

use App\Core\Entities\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Like extends BaseEntity
{
    use HasFactory;

    protected $table = 'likes';

    protected $guarded = ['request_by'];

    public $incrementing = false;

    public static function boot(){
        parent::boot();

        static::creating(function ($like) {
            $like->id = Str::uuid(36);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
