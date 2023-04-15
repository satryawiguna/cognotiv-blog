<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Like extends Model
{
    use HasFactory;

    public $incrementing = false;

    public static function boot(){
        parent::boot();

        static::creating(function ($like) {
            $like->id = Str::uuid(36);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
